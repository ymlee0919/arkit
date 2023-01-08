<?php

/**
 *
 */
class AccessTree
{

    /**
     * @var array
     */
    private array $tree;

    private string $sign;

    /**
     *
     */
    public function __construct()
    {
        $this->tree = [];
    }

    /**
     * Add a rule access to a given role. The rule access must have the fallowing structure:
     *
     *  - '*': For all rules
     *  - part.part...part: For a specific routing rule
     *  - part.part...*: For a group of rules starting with parts
     *  - part.part...[subpart.subpart;subpart;subpart....;subpart]: For a group of rules starting with a part
     * and end with a set of parts
     *
     * @param string $role Role
     * @param string $rule Rule to add
     * @return void
     */
    public function addAccessRule(string $role, string $rule) : void
    {
        if(!isset($this->tree[$role]))
            $this->tree[$role] = [];

        // If access rule is *, set it as the rule for this role
        if($rule === '*')
        {
            $this->tree[$role] = '*';
            return;
        }

        // Iterate for each part of the rule
        $this->addRulePart($this->tree[$role], $rule);
    }

    public function haveAccess(string $role, string $routingId) : bool
    {
        if(!isset($this->tree[$role]))
            throw new InvalidArgumentException('The rol "' . $role . '" do not exists.');

        if('*' === $this->tree[$role])
            return true;

        $ptr = &$this->tree[$role];
        $steps = explode('.', $routingId);
        $c = count($steps);
        for($i = 0; $i < $c; $i++)
        {
            if($ptr === '*')
                return true;

            $step = $steps[$i];

            if (in_array($step, $ptr) && $i + 1 == $c)
                return true;
            elseif(isset($ptr[$step]))
                $ptr = &$ptr[$step];
            else
                return false;
        }

        if($ptr === '*')
            return true;

        return false;
    }

    /**
     * Recursive method for add rule by part
     *
     * @param array $ptr
     * @param string $rulePart
     * @return void
     */
    private function addRulePart(array &$ptr, string $rulePart) : void
    {
        if('*' === $rulePart)
        {
            $ptr = '*';
            return;
        }

        if('[' === $rulePart[0])
        {
            $parts = explode(';',substr($rulePart, 1, -1));
            foreach ($parts as $part)
                $this->addRulePart($ptr, $part);
        }
        else
        {
            $index = strpos($rulePart, '.');
            if(false === $index)
                $ptr[] = $rulePart;
            else
            {
                $step = substr($rulePart, 0, $index);
                $rest = substr($rulePart, $index + 1);
                if(!isset($ptr[$step]))
                    $ptr[$step] = [];
                $this->addRulePart($ptr[$step], $rest);
            }
        }
    }

    /**
     * @return string
     */
    public function getSign(): string
    {
        return $this->sign;
    }

    /**
     * @param string $sign
     */
    public function setSign(string $sign): void
    {
        $this->sign = $sign;
    }

}