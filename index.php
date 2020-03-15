<pre>
<?php

include __DIR__.'/autoload.php';

class A
{
    public function e()
    {
        \x51\classes\events\Events::trigger('start', $this);
    }
}
class AB extends A
{
}
class ABC extends AB
{
}


\x51\classes\events\Events::on('start', function ($data) {
    echo "Default Start!\n<br>";
});
\x51\classes\events\Events::on('start', function ($data) {
    echo "A Start!\n<br>";
}, A::class);
\x51\classes\events\Events::on('start', function ($data) {
    echo "AB Start!\n<br>";
}, AB::class);
\x51\classes\events\Events::on('start', function ($data) {
    echo "ABC Start!\n<br>";
}, ABC::class);


$a = new A();
$ab = new AB();
$abc = new ABC();

$abc->e();
echo '<hr>';
$a->e();
echo '<hr>';
\x51\classes\events\Events::trigger('start');

