<?php
class Application 
{
    public $data;
    
    public function __construct($data) 
    {
        $this->data = $data;
    }
    
    public function run()
    {
        $data = $this->data;
        $game = new Game();
        foreach ($data as $key => $row)
        {
            $contestants[$key] = new Contestant($row);
        }
        $total = count($contestants);
        
        for($i = 0;$i < $total;$i++)
        {    
            for($j = $i+1;$j < $total;$j++)
            {
                $a = $contestants[$i];
                $b = $contestants[$j];
                $a->health = $data[$i]['Health'];
                $b->health = $data[$j]['Health'];
                var_dump($data[$i]);
                print '<br/>';
                var_dump($data[$j]);
                print '<br/>'; 
                $game->fight($a,$b);
            }
        }
        
        print "<br/>The Victory Rate<br/>";
        foreach ($contestants as $role)
        {
            print "{$role->name}<br/>";
            print "Victory Times: {$role->victory}  Failure Times: {$role->failure} Victory Rate: " . round(($role->victory / ($total-1)) * 100,2). "%";
            print "<br/><br/>";
            $victory_rate[] = $role->victory / ($total-1);
        }
        $keys = array_keys($victory_rate, max($victory_rate));
        
        print "<br/>The Best Contestant : <br/>";
        foreach ($keys as $key)
        {
            $role = $data[$key];
            var_dump($role);
        }
    }
}