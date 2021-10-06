<?php

/*
 * This file is part of ProgPilot, a static analyzer for security
 *
 * @copyright 2017 Eric Therond. All rights reserved
 * @license MIT See LICENSE at the root of the project for more info
 */


namespace progpilot\Transformations\Php\Exprs;

use PHPCfg\Op;
use PHPCfg\Operand;

use progpilot\Objects\MyDefinition;
use progpilot\Code\MyInstruction;
use progpilot\Code\Opcodes;

class ConstFetch
{
    public static function constFetch($context, $op, $expr)
    {
        echo "constFetch 1\n";

        if (isset($op)
            && $op instanceof Op\Expr\ConstFetch
                && isset($op->name)
                    && $op->name instanceof Operand\Literal) {
            echo "constFetch 2\n";

            $myTemp = new MyDefinition(
                $context->getCurrentBlock()->getId(),
                $context->getCurrentMyFile(),
                $context->getCurrentLine(),
                $context->getCurrentColumn(),
                $op->name->value
            );
            $myTemp->getCurrentState()->addType(MyDefinition::TYPE_CONSTANTE);

            $instDefChained = new MyInstruction(Opcodes::CONST_FETCH);
            $instDefChained->addProperty(MyInstruction::DEF, $myTemp);
            $instDefChained->addProperty(MyInstruction::RESULTID, $context->getCurrentFunc()->getOpId($op->result));
            $context->getCurrentMycode()->addCode($instDefChained);
        }
    }
}
