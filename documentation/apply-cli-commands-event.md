# Set Up CLI Commands

The CLI is a [Silly](https://github.com/mnapoli/silly) application.

In order to set up commands for the CLI application, an event is dispatched: `\RxAnte\AppBootstrap\Cli\ApplyCliCommandsEvent`. You'll need to subscribe to that event in order to set up CLI commands for your application.

```php
use RxAnte\AppBootstrap\Cli\ApplyCliCommandsEvent;

readonly class ApplyCommandsSubscriber
{
    public function onApplyCommands(ApplyCliCommandsEvent $commands): void
    {
        $commands->addCommand(
            'some:commmand',
            SomeInvokableClass::class,
        );

        $commands->addSymfonyCommand(SomeSymfonyCommand::class);
    }
}
```

## Methods

### `addCommand`

The `addCommand` method sets up a command based on the expression you want to use for the command (see [Silly documentation](https://github.com/mnapoli/silly/blob/master/docs/command-definition.md) to learn more about command expressions), a callable, string, or array as the second argument, and optionally, an array of aliases for the command.

If the second argument is a string, then it is assumed to be a classname to be loaded from the container.

See more about command callables in the [Silly documentation](https://github.com/mnapoli/silly/blob/master/docs/command-callables.md).

### `addSymfonyCommand`

Since Silly is a layer on top of the [Symfony Console Component](https://symfony.com/doc/current/components/console.html), you can add Symfony commands directly with this method. This is useful if you have already written a Symfony command and you wish to add it without modification to your app, or you have a package that supplies a command. You can pass in the instance of the command directly as an argument, or, you can pass in the classname string, and the class will be loaded from the container.
