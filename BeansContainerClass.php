<?php

class BeansContainerClass implements BeansContainer
{
	/**
	 * The capacity of the beans container in number of spoons
	 * @var float
	 */
	protected $beansContainerSize;

	/**
	 * The number of spoons of beans in the beans container
	 * @var integer
	 */
	protected $numSpoons = 0;

	/**
	 * Set the properties of the machine
	 * @param  integer $beansContainerSize   The capacity of the beans container
	 * @return void                        
	 */
	public function __construct($beansContainerSize = 50)
	{
		$this->beansContainerSize = $beansContainerSize;
	}

	public function addBeans($numSpoons)
	{
		$spaceForBeans = $this->beansContainerSize - $this->numSpoons;
		if( $numSpoons > $spaceForBeans )
		{

			throw new ContainerFullException("Trying to add {$numSpoons} spoons to {$spaceForBeans} spoons space. Not enough capacity.");
		} else {
			$this->numSpoons += $numSpoons;
		}
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
		// Check there's enough beans in the container
		if ( $numSpoons > $this->numSpoons )
		{
			throw new EspressoMachineContainerException("Not enough beans in the container");
			// Get all the beans from the container
			$availableSpoons = $this->numSpoons;
			$this->numSpoons = 0;
			return $availableSpoons;
		}
		// Reduce the beans by the amount requested
		$this->numSpoons -= $numSpoons;
		return $numSpoons;
	}

	/**
	* Returns the number of spoons of beans left in the container
	*
	* @return integer
	*/
	public function getBeans()
	{
		return $this->numSpoons;
	}
}