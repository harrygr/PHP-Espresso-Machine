<?php

define('LITRES_USED_PER_ESPRESSO', 0.05);
define('LITRES_USED_PER_DESCALE', 1);
define('LITRES_PER_DESCALE', 5);
define('BEANS_USED_PER_ESPRESSO', 1);
define('DECIMAL_PRECISION', 2);

bcscale(DECIMAL_PRECISION); // set the global scaling precision for calculations, this is handy for floating point calcs.

require_once('EspressoMachine.interface.php');
require_once('BeansContainerClass.php');
require_once('WaterContainerClass.php');
require_once('EspressoMachine.php');

/* 
 
// HOW TO USE:

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

*/