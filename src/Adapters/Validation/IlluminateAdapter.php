<?php

declare(strict_types=1);

namespace Fidelify\Api\Adapters\Validation;

const DS = DIRECTORY_SEPARATOR;

use Illuminate\Container\Container;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Translation\FileLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Factory;

class IlluminateAdapter
{
    public function __construct(
        private Factory $factory,
    ) {}

    public static function create(): self
    {
        $loader = new FileLoader(files: new Filesystem, path: dirname(dirname(dirname(__DIR__))) . DS .  'lang');
        $translator = new Translator(loader: $loader, locale: 'en');
        $factory = new Factory(translator: $translator, container: new Container);

        return new static($factory);
    }

    public function validate(array $data, array $rules): void
    {
        $validator = $this->factory->make(data: $data, rules: $rules);

        if ($validator->fails()) {
            $errors = json_encode(value: $validator->errors());
            throw new \Exception(message: $errors, code: 422);
        }
    }
}
