<?php

class WaterContainerClass implements WaterContainer
{
	/**
	 * The volume of the water container in litres
	 * @var float
	 */
	protected $waterContainerVolume;

	/**
	 * The number of litres of water in the water container
	 * @var float
	 */
	protected $litres = 0;

	/**
	 * Set the properties of the water container
	 * @param  float   $waterContainerVolume The capacity of the water container in litres
	 * @return void                        
	 */
	public function __construct( $waterContainerVolume = 2 )
	{

		$this->waterContainerVolume = $waterContainerVolume;
	}

	/**
	* Adds water to the coffee machine's water tank
	*
	* @param float $litres
	* @throws ContainerFullException
	*
	* @return void
	*/
	public function addWater($litres)
	{
		if ( $litres > ($this->waterContainerVolume - $this->litres) )
		{
			throw new ContainerFullException("Not enough volume left in the water container");
		}

		$this->litres = bcadd($this->litres, $litres);
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
		// Check there's enough water in the container
		if ( $litres > $this->litres )
		{
			throw new EspressoMachineContainerException("Not enough water in the container");
		}

		// Reduce the water by the amount requested
		$this->litres = bcsub($this->litres, $litres);

		return $litres;
	}

   /**
	* Returns the volume of water left in the container
	*
	* @return float number of litres
	*/
	public function getWater()
	{
		return $this->litres;
	}
}