<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\SmsService;

class PhoneFormattingTest extends TestCase
{
    /**
     * Test phone number formatting with various inputs
     *
     * @dataProvider phoneNumberProvider
     */
    public function test_phone_number_formatting($input, $expected)
    {
        $smsService = new SmsService();
        
        // Use reflection to access private method
        $reflection = new \ReflectionClass($smsService);
        $method = $reflection->getMethod('formatPhoneNumber');
        $method->setAccessible(true);
        
        $result = $method->invoke($smsService, $input);
        
        $this->assertEquals($expected, $result, "Failed to format: $input");
    }
    
    /**
     * Data provider for phone number tests
     */
    public function phoneNumberProvider()
    {
        return [
            // International format with +
            ['+251912345678', '251912345678'],
            ['+251712345678', '251712345678'],
            
            // International format without +
            ['251912345678', '251912345678'],
            ['251712345678', '251712345678'],
            
            // Local format with 0
            ['0912345678', '251912345678'],
            ['0712345678', '251712345678'],
            
            // Local format without 0
            ['912345678', '251912345678'],
            ['712345678', '251712345678'],
            
            // With spaces
            ['+251 912 345 678', '251912345678'],
            ['0912 345 678', '251912345678'],
            
            // With dashes
            ['+251-912-345-678', '251912345678'],
            ['0912-345-678', '251912345678'],
            
            // With mixed separators
            ['+251 (912) 345-678', '251912345678'],
            ['09 12 34 56 78', '251912345678'],
        ];
    }
    
    /**
     * Test validation regex pattern
     *
     * @dataProvider validationProvider
     */
    public function test_phone_validation($input, $shouldPass)
    {
        $pattern = '/^(\+?251|0)?[79]\d{8}$/';
        $matches = preg_match($pattern, $input);
        
        if ($shouldPass) {
            $this->assertEquals(1, $matches, "Should pass validation: $input");
        } else {
            $this->assertEquals(0, $matches, "Should fail validation: $input");
        }
    }
    
    /**
     * Data provider for validation tests
     */
    public function validationProvider()
    {
        return [
            // Valid numbers
            ['+251912345678', true],
            ['251912345678', true],
            ['0912345678', true],
            ['912345678', true],
            ['+251712345678', true],
            ['0712345678', true],
            ['712345678', true],
            
            // Invalid numbers (wrong prefix)
            ['0812345678', false],  // 8 is not valid
            ['0612345678', false],  // 6 is not valid
            ['0512345678', false],  // 5 is not valid
            
            // Invalid numbers (wrong length)
            ['09123456', false],     // Too short
            ['091234567', false],    // Too short
            ['09123456789', false],  // Too long
            ['0912345678910', false], // Too long
            
            // Invalid numbers (wrong format)
            ['1234567890', false],   // Wrong country
            ['+1234567890', false],  // Wrong country code
            ['abc912345678', false], // Contains letters
        ];
    }
}

