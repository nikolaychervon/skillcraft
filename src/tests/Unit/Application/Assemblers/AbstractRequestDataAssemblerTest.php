<?php

namespace Tests\Unit\Application\Assemblers;

use PHPUnit\Framework\TestCase;
use App\Application\Shared\Exceptions\RequestData\RequestDataAssemblyException;
use Tests\Fakes\RequestDataAssembler\TestNoConstructorRequestDataAssembler;
use Tests\Fakes\RequestDataAssembler\TestUserRequestData;
use Tests\Fakes\RequestDataAssembler\TestUserRequestDataAssembler;

class AbstractRequestDataAssemblerTest extends TestCase
{
    private TestUserRequestDataAssembler $assembler;

    protected function setUp(): void
    {
        parent::setUp();
        $this->assembler = new TestUserRequestDataAssembler();
    }

    public function test_it_creates_request_data_from_camel_case_array(): void
    {
        $requestData = $this->assembler->assemble([
            'firstName' => 'John',
            'lastName'  => 'Doe',
        ]);

        $this->assertInstanceOf(TestUserRequestData::class, $requestData);
        $this->assertEquals('John', $requestData->firstName());
        $this->assertEquals('Doe', $requestData->lastName());
        $this->assertNull($requestData->middleName());
    }

    public function test_it_creates_request_data_from_snake_case_array(): void
    {
        $requestData = $this->assembler->assemble([
            'first_name' => 'Jane',
            'last_name'  => 'Doe',
        ]);

        $this->assertEquals('Jane', $requestData->firstName());
        $this->assertEquals('Doe', $requestData->lastName());
    }

    public function test_it_uses_default_value_for_optional_fields(): void
    {
        $requestData = $this->assembler->assemble([
            'firstName' => 'Mike',
            'lastName'  => 'Smith',
        ]);

        $this->assertNull($requestData->middleName());
    }

    public function test_it_throws_exception_when_required_field_missing(): void
    {
        $this->expectException(RequestDataAssemblyException::class);

        $this->assembler->assemble([
            'firstName' => 'John',
        ]);
    }

    public function test_it_throws_exception_when_request_data_has_no_constructor(): void
    {
        $this->expectException(RequestDataAssemblyException::class);

        $assembler = new TestNoConstructorRequestDataAssembler();
        $assembler->assemble([]);
    }
}
