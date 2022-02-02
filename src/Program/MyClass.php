<?php

namespace Program;

use IO\Console;
use Threading\SuperGlobalArray;
use Threading\Thread;

class MyClass extends Thread
{
    public function Threaded(array $args) : void
    {
        Console::WriteLine("[child] arguments");
        var_dump($args);

        $superGlobalArray = SuperGlobalArray::GetInstance(); // There is some example with Super global array

        $i = 0;
        /** @var Main $parent */$parent = $this->GetParentThreadedObject(); // Let's PHPStorm think that it's really "Main"
        $a = 0;
        while (true)
        {
            $i++;
            Console::WriteLine("[child] MyClass " . $i);
            if ($i == 10)
            {
                $superGlobalArray->Set(["marco"], "polo!");
                $parent->thinkUpToVarName = 0; // now child thread is frozen until parent thread call WaitForChildAccess()
                $parent->printSomething();
                Console::WriteLine("[child] " . $superGlobalArray->Get(["foo"]));
                $this->FinishSychnorization(); // unblocks parent thread and stop sync
            }
            if ($i == 15)
            {
                $a = $parent->Sqr(5);
                $this->FinishSychnorization();
                Console::WriteLine("[child] Result: " . $a);
            }
            sleep(2);
        }
    }
}