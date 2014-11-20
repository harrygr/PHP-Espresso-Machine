<?php

class EspressoMachine implements EspressoMachineInterface
{
	/**
	 * The container to hold the beans
	 * @var BeansContainer
	 */
	protected $beansContainer;

	/**
	 * The container to hold the water
	 * @var WaterContainer
	 */
	protected $waterContainer;

	/**
	 * Determines whether the water supply is connected to mains (water is infinate)
	 * @var boolean
	 */
	protected $waterSupplyIsMains;

	/**
	 * The number of litres used to make espresson since the machine was last descaled
	 * @var integer
	 */
	protected $litresSinceLastDescale = 0;

	/**
	 * Set the properties of the machine
	 * @param  boolean $waterSupplyIsMains   Is the machine connected to mains water?
	 * @return void                        
	 */
	public function __construct( $waterSupplyIsMains = false )
	{
		$this->waterSupplyIsMains = $waterSupplyIsMains;
	}

	/**
	 * Adds beans to the container
	 *
	 * @param integer $numSpoons number of spoons of beans
	 * @throws ContainerFullException, EspressoMachineContainerException
	 *
	 * @return void
	 */
	public function addBeans($numSpoons)
	{
		if ( !$this->hasBeansContainer() )
		{
			throw new EspressoMachineContainerException("The machine hasn't got a bean container");
		}
		$this->beansContainer->addBeans($numSpoons);
	}

	/**
	 * Get $numSpoons from the container
	 *
	 * @throws EspressoMachineContainerException
	 * @param integer $numSpoons number of spoons of beans
	 * @return integer
	 */
	public function useBeans($numSpoons)
	{
		if ( !$this->hasBeansContainer() )
		{
			throw new EspressoMachineContainerException("The machine hasn't got a bean container");
		}
		$this->beansContainer->useBeans($numSpoons);
	}

	/**
	 * Returns the number of spoons of beans left in the container
	 *
	 * @return integer
	 */
	public function getBeans()
	{
		return $this->beansContainer->getBeans();
	}

	/**
	 * Adds water to the coffee machine's water tank
	 *
	 * @param float $litres
	 * @throws ContainerFullException, EspressoMachineContainerException
	 *
	 * @return void
	 */
	public function addWater($litres)
	{
		if ( $this->waterSupplyIsMains )
		{
			throw new EspressoMachineContainerException("Water cannot be added as the machine is on mains supply");
		}

		$this->waterContainer->addWater($litres);
	}

	/**
	 * Use $litres from the container
	 *
	 * @throws EspressoMachineContainerException
	 * @param float $litres
	 * @return integer
	 */
	public function useWater($litres)
	{
		// Only use water from the container if there's no mains supply
		if ( !$this->waterSupplyIsMains )
		{
			return $this->waterContainer->useWater($litres);
		}

		if ($this->waterSupplyIsMains)
		{
			throw new EspressoMachineContainerException("The machine is on mains supply");
		}

		if ( !$this->hasWaterContainer() )
		{
			throw new EspressoMachineContainerException("The machine hasn't got a water container");
		}

		// The machine is on the mains so has infinite water
		return $litres;
	}

	/**
	 * Returns the volume of water left in the container
	 *
	 * @return float number of litres
	 */
	public function getWater()
	{
		if ($this->waterSupplyIsMains)
		{
			throw new EspressoMachineContainerException("The machine is on mains supply");
		}

		if ( !$this->hasWaterContainer() )
		{
			throw new EspressoMachineContainerException("The machine hasn't got a water container");
		}

		return $this->waterContainer->getWater();
	}

  /**
   * Runs the process to descale the machine
   * so the machine can be used make coffee
   * uses 1 litre of water
   *
   * @throws NoWaterException
   *
   * @return void
   */
  public function descale()
  {
  	try 
  	{
  		$this->useWater(LITRES_USED_PER_DESCALE);
  		$this->litresSinceLastDescale = 0;
  	} 
  	catch (EspressoMachineContainerException $e) 
  	{
  		throw new NoWaterException("Not enough water to descale. {$this->getWater()} litres remaining.");
  	}
  }

	/**
	 * Runs the process for making any number of Espressos
	 *
	 * @throws DescaleNeededException, NoBeansException, NoWaterException
	 *
	 * @return float of litres of coffee made
	 */
	protected function makeEspressos($quantity)
	{
		// Check the machine doesn't need a descale
		if ($this->litresSinceLastDescale > LITRES_PER_DESCALE)
		{
			throw new DescaleNeededException("The machine needs descaling. {$this->litresSinceLastDescale} litres since last descale.");
		}

		try {
			$this->useBeans(BEANS_USED_PER_ESPRESSO * $quantity);
		} 
		catch (EspressoMachineContainerException $e) 
		{
			throw new NoBeansException("Not enough beans to make an espresso. {$this->getBeans()} spoons remaining.");
		}

		try {
			$this->useWater(LITRES_USED_PER_ESPRESSO * $quantity);
		} 
		catch (EspressoMachineContainerException $e) 
		{
			throw new NoWaterException("Not enough water to make an espresso. {$this->getWater()} litres remaining.");
		}

		$litres_of_coffee_made = LITRES_USED_PER_ESPRESSO * $quantity;
		$this->litresSinceLastDescale = bcadd($this->litresSinceLastDescale, $litres_of_coffee_made);

		return $litres_of_coffee_made;
	}

    /**
	 * Runs the process for making Espresso
	 *
	 * @throws DescaleNeededException, NoBeansException, NoWaterException
	 *
	 * @return float of litres of coffee made
	 */
    public function makeEspresso()
    {
    	return $this->makeEspressos(1);
    }

	/**
	 * @see makeEspresso
	 * @throws DescaleNeededException, NoBeansException, NoWaterException
	 *
	 * @return float of litres of coffee made
	 */
	public function makeDoubleEspresso()
	{
		return $this->makeEspressos(2);
	}

	/**
	 * This method controls what is displayed on the screen of the machine
	 * Returns ONE of the following human readable statuses in the following preference order:
	 *
	 * Descale needed
	 * Add beans and water
	 * Add beans
	 * Add water
	 * {Integer} Espressos left
	 *
	 * @return string
	 */
	public function getStatus()
	{
		if ( $this->litresSinceLastDescale >= LITRES_PER_DESCALE)
		{
			if ( !$this->waterSupplyIsMains && $this->getWater() < LITRES_USED_PER_DESCALE )
			{
				return "Add water";
			}
			return "Descale needed";
		}

		if ( $this->getBeans() < BEANS_USED_PER_ESPRESSO && $this->getWater() < LITRES_USED_PER_ESPRESSO)
		{
			return "Add beans and water";
		}

		if ( $this->getBeans() < BEANS_USED_PER_ESPRESSO )
		{
			return "Add beans";
		}

		if ( !$this->waterSupplyIsMains && $this->getWater() < LITRES_USED_PER_ESPRESSO )
		{
			return "Add water";
		}

		$espressos_worth_of_beans = intval(bcdiv($this->getBeans(), BEANS_USED_PER_ESPRESSO));
		$espressos_left = $espressos_worth_of_beans;

		if ( !$this->waterSupplyIsMains )
		{
			$espressos_worth_of_water = intval(bcdiv($this->getWater(), LITRES_USED_PER_ESPRESSO));			
			$espressos_left = min($espressos_worth_of_beans, $espressos_worth_of_water);
		}

		return "$espressos_left Espressos Left";
	}


	/**
	 * @param BeansContainer $container
	 */
	public function setBeansContainer(BeansContainer $container)
	{
		$this->beansContainer = $container;
	}

	/**
	 * @return BeansContainer
	 */
	public function getBeansContainer()
	{
		return $this->beansContainer;
	}

	/**
	 * @param WaterContainer $container
	 */
	public function setWaterContainer(WaterContainer $container)
	{
		$this->waterContainer = $container;
	}

	/**
	 * Says if the machine has a water container attached
	 * @return boolean
	 */
	public function hasWaterContainer()
	{
		return $this->waterContainer instanceof WaterContainer;
	}

	/**
	 * @return WaterContainer
	 */
	public function getWaterContainer()
	{
		return $this->waterContainer;
	}

	/**
	 * Says if the machine has a beans container attached
	 * @return boolean
	 */
	public function hasBeansContainer()
	{
		return $this->beansContainer instanceof BeansContainer;
	}
}
