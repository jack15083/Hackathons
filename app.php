<?php 
$data = array(
    array('name' => 'Tom Cruise','health' => 136, 'damage' => 6, 'attacks' => 4, 'dodge' => 10, 'critical' => 10,'initiative' => 4),
    array('name' => 'Sponge Bob','health' => 110, 'damage' => 4, 'attacks' => 5, 'dodge' => 12, 'critical' => 12,'initiative' => 5),
    array('name' => 'James Earl Jones','health' => 175, 'damage' => 8, 'attacks' => 3, 'dodge' => 2, 'critical' => 8,'initiative' => 3),
    array('name' => 'Bob Barker','health' => 112, 'damage' => 2, 'attacks' => 8, 'dodge' => 4, 'critical' => 16,'initiative' => 2),
    array('name' => 'Tonya Harding','health' => 108, 'damage' => 7, 'attacks' => 4, 'dodge' => 12, 'critical' => 18,'initiative' => 4),
    array('name' => 'Charles Barkley','health' => 220, 'damage' => 12, 'attacks' => 2, 'dodge' => 4, 'critical' => 10,'initiative' => 2),
    array('name' => 'Peter Piper','health' => 116, 'damage' => 4, 'attacks' => 6, 'dodge' => 14, 'critical' => 14,'initiative' => 6),
    array('name' => 'Harry Potter','health' => 96, 'damage' => 16, 'attacks' => 2, 'dodge' => 16, 'critical' => 15,'initiative' => 6),
    array('name' => 'Shamu','health' => 280, 'damage' => 24, 'attacks' => 1, 'dodge' => 0, 'critical' => 0,'initiative' => 0),
    array('name' => 'Bill Gates','health' => 124, 'damage' => 6, 'attacks' => 4, 'dodge' => 8, 'critical' => 12,'initiative' => 4),
        
);
$app = new Application($data);
$app->run();

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
                $a->health = $data[$i]['health'];
                $b->health = $data[$j]['health'];
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

class Contestant 
{
    private $name;
    private $health;
    private $damage;
    private $attacks;
    private $dodge;
    private $critical;
    private $initiative;
    private $victory = 0;
    private $failure = 0;
    
    public function __construct($data) 
    {
        $this->name       = $data['name'];
        $this->health     = $data['health'];
        $this->damage     = $data['damage'];
        $this->attacks    = $data['attacks'];
        $this->dodge      = $data['dodge'];
        $this->critical   = $data['critical'];
        $this->initiative = $data['initiative'];
    }
    
    private function __set($key,$value) 
    {
        $this->$key = $value;
    }
    
    private function __get($key) 
    {
        return $this->$key;
    }
}