<?php

class SettingsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException Exception
     * @expectedExceptionMessage Settings file did not return a filled array of items
     */
    public function test_load_settings_from_empty_file()
    {
        Settings::loadFromFile(__DIR__ . '/../fixtures/emptysettings.php');
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage Could not load and/or read from settings file 'doesnotexist.php'
     */
    public function test_load_settings_from_nonexisting_file()
    {
        Settings::loadFromFile('doesnotexist.php');
    }

    public function test_load_settings_from_file()
    {
        $result = Settings::loadFromFile(__DIR__ . '/../fixtures/settings.php');

        $this->assertTrue($result);

        $expected = [
            'database' => [
                'username' => 'root',
                'password' => '',
                'db_name' => 'settings'
            ],
            'logging' => [
                'loglevel' => 'debug',
                'logfile' => 'debug.log'
            ],
        ];

        $this->assertSame($expected, Settings::getAll());
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage Settings array is not a filled array of items
     */
    public function test_load_settings_from_empty_array()
    {
        Settings::loadFromArray([]);
    }

    public function test_load_settings_from_array()
    {
        Settings::clear();

        $settings = [
            'database' => [
                'username' => 'root',
                'password' => '',
                'db_name' => 'settings'
            ],
            'logging' => [
                'loglevel' => 'debug',
                'logfile' => 'debug.log'
            ],
        ];

        $result = Settings::loadFromArray($settings);

        $this->assertTrue($result);

        $this->assertSame($settings, Settings::getAll());
    }

    public function test_get_setting_with_nonexisting_value()
    {
        Settings::loadFromFile(__DIR__ . '/../fixtures/settings.php');

        $this->assertSame('123', Settings::get('not.exist', '123'));
    }

    public function test_get_setting_value()
    {
        Settings::loadFromFile(__DIR__ . '/../fixtures/settings.php');

        $this->assertSame('root', Settings::get('database.username'));
        $this->assertSame('debug.log', Settings::get('logging...logfile'));
    }

    public function test_set_setting_value()
    {
        Settings::loadFromFile(__DIR__ . '/../fixtures/settings.php');
        $this->assertSame('root', Settings::get('database.username'));

        Settings::set('database.username', 'mysql');
        $this->assertSame('mysql', Settings::get('database.username'));

        Settings::set('logging.enabled', false);
        $this->assertSame(false, Settings::get('logging.enabled'));
    }

    public function test_has_setting_value()
    {
        Settings::loadFromFile(__DIR__ . '/../fixtures/settings.php');

        $this->assertFalse(
            Settings::has('does.not.exist')
        );

        $this->assertTrue(
            Settings::has('logging.loglevel')
        );

        $this->assertTrue(
            Settings::has('database....password')
        );
    }
}
