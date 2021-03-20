<?php

namespace Tests;

use Bdelespierre\HasUuid\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Orchestra\Testbench\TestCase;

class HasUuidTest extends TestCase
{
    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

        // used for UUID v3 & v5 namespaces
        $app['config']->set('app.key', 'base64:BoshQ0cZA0GcH7DVkOfSR4KiBtgdjfOMbLlatHp1FM8=');
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
    }

    protected function makeModel()
    {
        return new class extends Model {
            use HasUuid;
            protected $table = 'users';
            protected $fillable = ['name', 'email'];
        };
    }

    protected function getProtectedPropertyValue(object $object, string $name)
    {
        $property = (new \ReflectionClass($object))->getProperty($name);
        $property->setAccessible(true);

        return $property->getValue($object);
    }

    public function testSave()
    {
        $model = $this->makeModel();
        $model->fill(['name' => 'Steve BUSCEMI', 'email' => 'steve.buscemi@example.com']);
        $model->save();

        $this->assertMatchesRegularExpression(
            '/^[0-9A-F]{8}-[0-9A-F]{4}-[4][0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i',
            $model->id,
            "Model equipped with the 'HasUuid' trait should generate valid v4 UUID upon creation"
        );
    }

    public function testGetKeyType()
    {
        $this->assertEquals(
            'string',
            $this->makeModel()->getKeyType(),
            "Model equipped with the 'HasUuid' trait key type should be 'string'"
        );

        // test property value (as shown in dump/debug)
        $this->assertEquals(
            'string',
            $this->getProtectedPropertyValue($this->makeModel(), 'keyType'),
            "Model equipped with the 'HasUuid' trait keyType property should be 'string'"
        );
    }

    public function testGetIncrementing()
    {
        $this->assertFalse(
            $this->makeModel()->getIncrementing(),
            "Model equipped with the 'HasUuid' trait should not increment their id"
        );

        // test property value (as shown in dump/debug)
        $this->assertFalse(
            $this->makeModel()->incrementing,
            "Model equipped with the 'HasUuid' trait incrementing property should be false"
        );
    }

    public function testResolveRouteBinding()
    {
        $this->assertNull(
            $this->makeModel()->resolveRouteBinding(123),
            "Model equipped with the 'HasUuid' trait should not resolve on numeric ids"
        );

        $this->assertNull(
            $this->makeModel()->resolveRouteBinding('not an UUID'),
            "Model equipped with the 'HasUuid' trait should only resolve on UUID strings"
        );

        $this->assertTrue(
            $this->makeModel()->resolveRouteBinding('1d4579a2-85b1-4872-931e-031eefab974b')->exists,
            "Model equipped with the 'HasUuid' trait should resolve on valid UUID strings"
        );

        $this->assertTrue(
            $this->makeModel()->resolveRouteBinding('d449075e-1f6d-4464-8c82-9a403dfcc9fd')->exists,
            "Model equipped with the 'HasUuid' trait should resolve on valid UUID strings"
        );

        $this->assertTrue(
            $this->makeModel()->resolveRouteBinding('af7d82e7-1dc6-42c7-8e4f-57a508b3a402')->exists,
            "Model equipped with the 'HasUuid' trait should resolve on valid UUID strings"
        );
    }

    public function testGetUuidV1()
    {
        $model = $this->makeModel();
        $model->uuidVersion = 1;

        $this->assertMatchesRegularExpression(
            '/^[0-9A-F]{8}-[0-9A-F]{4}-[1][0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i',
            $model->getUuid(),
            "Model equipped with the 'HasUuid' trait should generate valid v1 UUID"
        );
    }

    public function testGetUuidV2()
    {
        $this->expectException(\Exception::class);
        $this->expectDeprecationMessageMatches('/Version 2 is unsupported./');

        $model = $this->makeModel();
        $model->uuidVersion = 2;
        $model->getUuid();
    }

    public function testGetUuidV3()
    {
        $model = $this->makeModel()->fill(['email' => 'danny.devito@example.com']);
        $model->uuidVersion = 3;
        $model->uuidNode = ':email';

        $this->assertMatchesRegularExpression(
            '/^[0-9A-F]{8}-[0-9A-F]{4}-[3][0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i',
            $model->getUuid(),
            "Model equipped with the 'HasUuid' trait should generate valid v3 UUID"
        );

        $this->assertEquals(
            'e7edf144-7809-3027-b8c5-30fb4a961ab4',
            $model->getUuid(),
            "Model equipped with the 'HasUuid' trait should generate v3 UUID"
        );
    }

    public function testGetUuidV4()
    {
        $model = $this->makeModel();

        $this->assertMatchesRegularExpression(
            '/^[0-9A-F]{8}-[0-9A-F]{4}-[4][0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i',
            $model->getUuid(),
            "Model equipped with the 'HasUuid' trait should generate valid v4 UUID by default"
        );
    }

    public function testGetUuidV5()
    {
        $model = $this->makeModel()->fill(['email' => 'silvester.stallone@example.com']);
        $model->uuidVersion = 5;
        $model->uuidNode = ':email';

        $this->assertMatchesRegularExpression(
            '/^[0-9A-F]{8}-[0-9A-F]{4}-[5][0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i',
            $model->getUuid(),
            "Model equipped with the 'HasUuid' trait should generate valid v5 UUID"
        );

        $this->assertEquals(
            'e1c84d29-2fb4-5e2b-8a3b-660045657ee1',
            $model->getUuid(),
            "Model equipped with the 'HasUuid' trait should generate v3 UUID"
        );
    }

    public function testGetUuidVersion()
    {
        $model = $this->makeModel();

        $this->assertEquals(
            4,
            $model->getUuidVersion(),
            'UUID version should be 4 by default'
        );

        $model->uuidVersion = 3;

        $this->assertEquals(
            3,
            $model->getUuidVersion(),
            "Model equipped with the 'HasUuid' trait can change their UUID version using an attribute"
        );
    }

    public function testGetUuidNode()
    {
        $model = $this->makeModel();

        $this->assertNull(
            $model->getUuidNode(),
            'UUID node should be NULL by default'
        );

        $model->uuidNode = '123456';

        $this->assertEquals(
            '123456',
            $model->getUuidNode(),
            "Model equipped with the 'HasUuid' trait can change their UUID node using an attribute"
        );

        $model->fill(['email' => 'bruce.willis@example.com']);
        $model->uuidNode = ':email';

        $this->assertEquals(
            'bruce.willis@example.com',
            $model->getUuidNode(),
            "Model equipped with the 'HasUuid' trait can change their UUID node using a property attribute"
        );
    }

    public function testGetUuidNamespace()
    {
        $model = $this->makeModel();

        $this->assertEquals(
            '82a206d81d8df38c6cb95ab47a7514cf',
            $model->getUuidNamespace(),
            'UUID namespace should be hash of app key by default'
        );

        $this->app['config']->set('app.key', '757787f54ace4bf38b838b8e0ad80c78');

        $this->assertEquals(
            '38623833386238653061643830633738',
            $model->getUuidNamespace(),
            'UUID namespace should be hash of app key by default'
        );

        $model->uuidNamespace = 'e51e84234918424aacf6fb70945fd83c';

        $this->assertEquals(
            'e51e84234918424aacf6fb70945fd83c',
            $model->getUuidNamespace(),
            "Model equipped with the 'HasUuid' trait can change their UUID namespace using an attribute"
        );

        $model->fill(['email' => 'bruce.willis@example.com']);
        $model->uuidNamespace = ':email';

        $this->assertEquals(
            'bruce.willis@example.com',
            $model->getUuidNamespace(),
            "Model equipped with the 'HasUuid' trait can change their UUID namespace using a property attribute"
        );
    }
}
