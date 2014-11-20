PHP-Espresso-Machine
====================

An interface and class for an espresso machine.

## Usage

1. Include/Require `main.php`

2. Instantiate and run the machine. Example:

```php
// Specify our machine's properties

$beansContainerCapacity = 50; // spoons
$waterContainerCapacity = 2; // litres
$hasMainsWater = true;

// Instantiate the coffee machine
$machine = new EspressoMachine($hasMainsWater);

// Add the containers
$machine->setBeansContainer(new BeansContainerClass($beansContainerCapacity));
$machine->setWaterContainer(new WaterContainerClass($waterContainerCapacity));


// Fill the machine with beans and water
$machine->addBeans(50);
$machine->addWater(2);

// Check all is good
echo $machine->getStatus() . PHP_EOL;

// make some coffee and check status as we do so
for ($i = 0; $i < 50; $i++)
{
	$machine->makeDoubleEspresso();
	echo $machine->getStatus() . PHP_EOL;

	if ($machine->getWater() < LITRES_USED_PER_ESPRESSO)
	{
		$machine->addWater(2 - $machine->getWater());
	}

	if ($machine->getBeans() < BEANS_USED_PER_ESPRESSO)
	{
		$machine->addBeans(50 - $machine->getBeans());
	}
}
```
