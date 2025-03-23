<?php
require_once 'Collatz_class.php';

class CollatzHistogram extends Collatz {
    public const DEFAULT_START = 1;
    public const DEFAULT_END = 100;
    public const MAX_ITERATIONS = 1000;

    private array $histogram = [];

    public function calculateHistogram(int $start, int $end): array {
        if ($start > $end) {
            throw new InvalidArgumentException("Start value must be less than or equal to end value.");
        }
        $this->histogram = [];
        for ($i = $start; $i <= $end; $i++) {
            $sequence = $this->iterate($i);
            $iterations = count($sequence) - 1;
            if ($iterations > self::MAX_ITERATIONS) {
                $iterations = self::MAX_ITERATIONS;
            }
            if (!isset($this->histogram[$iterations])) {
                $this->histogram[$iterations] = 0;
            }
            $this->histogram[$iterations]++;
        }
        ksort($this->histogram);
        return $this->histogram;
    }
}
?>
