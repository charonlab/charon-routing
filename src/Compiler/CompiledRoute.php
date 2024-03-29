<?php

/*
 * This file is part of the charonlab/charon-routing.
 *
 * Copyright (C) 2023-2024 Charon Lab Development Team
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE.md file for details.
 */

namespace Charon\Routing\Compiler;

final readonly class CompiledRoute
{
    public function __construct(
        /** @var non-empty-string $regex */
        private string $regex,
        /** @var array $variables */
        private array $variables
    ) {
    }

    /**
     * @return non-empty-string
     */
    public function getRegex(): string {
        return $this->regex;
    }

    public function getVariables(): array {
        return $this->variables;
    }
}
