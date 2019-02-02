<?php
//Subject
interface Subject
{
    public function registerObserver(Observer $o);

    public function removeObserver(Observer $o);
    public function notifyObservers();

}
//Observer
interface Observer
{
    public function update($temp, $humidity, $pressure);
}

interface DisplayElement
{
    public function display();
}


class WeatherData implements Subject
{

    private $observers; //ArrayList
    private $temperature;
    private $humidity;
    private $pressure;

    public function __construct()
    {
        $this->observers = array();
    }

    public function registerObserver(Observer $o)
    {
        array_push($this->observers, $o);
    }

    public function removeObserver($posicion  /*Observer $o*/ )
    {
        $i = $posicion;

        if ($i >= 0)
            unset($this->observers[$i]);
    }

    public function notifyObservers()
    {

        for ($i = 0; $i < count($this->observers); ++$i) {

            $observer = $this->observers[$i];  // almacena un objeto observer de forma temporal
            $observer->update($this->temperature, $this->humidity, $this->pressure);

        }

    }
    public function measurementsChanged()
    {
        $this->notifyObservers();
    }

    public function setMeasuremets($temperature, $humidity, $pressure)
    {
        $this->temperature = $temperature;
        $this->humidity = $humidity;
        $this->pressure = $pressure;
        $this->measurementsChanged();
    }

}

class CurrentConditionsDisplay implements Observer, DisplayElement
{
    private $temperature;
    private $humidity;
    private $weatherData;

    public function __construct(Subject $weatherData)
    {
        $this->weatherData = $weatherData;
        $weatherData->registerObserver($this);
    }
    public function update($temperature, $humidity, $pressure)
    {
        $this->temperature = $temperature;
        $this->humidity = $humidity;
        $this->display();
    }

    public function display()
    {
        echo "Current conditions: " . $this->temperature . " F degrees and " . $this->humidity . " % humidity <br>";
    }

}

class WeatherStation
{
    public function principal()
    {
        $weatherData = new WeatherData();
        $currentDisplay = new CurrentConditionsDisplay($weatherData);

        $weatherData->setMeasuremets(80, 65, 30.4);
        $weatherData->setMeasuremets(82, 70, 29.2);
        $weatherData->setMeasuremets(78, 90, 29.2);
    }
}
$principal = new WeatherStation;
$principal->principal();
?>
