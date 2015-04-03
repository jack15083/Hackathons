<?php
class Game 
{    
    public function hit($a,$b) 
    {
        $is_critical = false;
        $is_dodge = false;
        //check critical;
        $crand = rand(1,100);
        if($crand <= $a->critical) 
        {
            $damage = $a->damage * 2;
            $is_critical = true;
        }
        else 
        {
            $damage = $a->damage;
        }
        $drand = rand(1, 100);
        if($drand <= $b->dodge)
        {
            $damage = 0;
            $is_dodge = true;
        }
        $b->health = $b->health - $damage;
        
        print "{$a->name} hits {$b->name} for $damage damage (" . ($b->health + $damage) ." -> $b->health)" .  ($is_dodge ? "(Dodge)" : ($is_critical ?  "(Critical)" : ''));
        print "<br/>";
        if($b->health <= 0)
        {
            print "{$a->name} wins!";
            $a->victory++;
            $b->failure++;
            print "<br/>";
            return "done";
        }
        if($a->health <= 0)
        {
            print "{$b->name} wins!";
            $b->victory++;
            $a->failure++;
            print "<br/>";
            return "done";
        }
        return 'continue';        
    }
    
    public function round($a,$b,$round)
    {
        $temp = $a;
        if($a->initiative < $b->initiative)
        {
            $a = $b;
            $b = $temp;
        }
        print "<br/>Round $round:<br/>";
        print "{$a->name} is randomly selected to go first ($a->initiative > $b->initiative)  <-- initiative roll";
        print "<br/>";
        $Aattacks = $a->attacks;
        $Battacks = $b->attacks;
        for ($Aattacks;$Aattacks > 0;$Aattacks--)    
        {
            $result = $this->hit($a,$b);
            if($result == 'done') return ;
            if($Battacks > 0)
            {
                $result = $this->hit($b,$a);
                if($result == 'done') return ;
            }            
            $Battacks--;
        }
        for($Battacks;$Battacks > 0;$Battacks--)
        {
            $result = $this->hit($b,$a);
            if($result == 'done') return ;
        }
        $round++;
        $this->round($a, $b,$round);
    }
    
    public function fight($a,$b)
    {
        $this->round($a, $b, 1);
    }
}