<?php
namespace RWB\Rwbfeupload\Tests\Unit\Controller;
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2016 Falk Röder <mail@falk-roeder.de>, Röder Webdesign Berlin
 *  			
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Test case for class RWB\Rwbfeupload\Controller\MainController.
 *
 * @author Falk Röder <mail@falk-roeder.de>
 */
class MainControllerTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{

	/**
	 * @var \RWB\Rwbfeupload\Controller\MainController
	 */
	protected $subject = NULL;

	public function setUp()
	{
		$this->subject = $this->getMock('RWB\\Rwbfeupload\\Controller\\MainController', array('redirect', 'forward', 'addFlashMessage'), array(), '', FALSE);
	}

	public function tearDown()
	{
		unset($this->subject);
	}

	/**
	 * @test
	 */
	public function listActionFetchesAllMainsFromRepositoryAndAssignsThemToView()
	{

		$allMains = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array(), array(), '', FALSE);

		$mainRepository = $this->getMock('RWB\\Rwbfeupload\\Domain\\Repository\\MainRepository', array('findAll'), array(), '', FALSE);
		$mainRepository->expects($this->once())->method('findAll')->will($this->returnValue($allMains));
		$this->inject($this->subject, 'mainRepository', $mainRepository);

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$view->expects($this->once())->method('assign')->with('mains', $allMains);
		$this->inject($this->subject, 'view', $view);

		$this->subject->listAction();
	}

	/**
	 * @test
	 */
	public function showActionAssignsTheGivenMainToView()
	{
		$main = new \RWB\Rwbfeupload\Domain\Model\Main();

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$this->inject($this->subject, 'view', $view);
		$view->expects($this->once())->method('assign')->with('main', $main);

		$this->subject->showAction($main);
	}

	/**
	 * @test
	 */
	public function createActionAddsTheGivenMainToMainRepository()
	{
		$main = new \RWB\Rwbfeupload\Domain\Model\Main();

		$mainRepository = $this->getMock('RWB\\Rwbfeupload\\Domain\\Repository\\MainRepository', array('add'), array(), '', FALSE);
		$mainRepository->expects($this->once())->method('add')->with($main);
		$this->inject($this->subject, 'mainRepository', $mainRepository);

		$this->subject->createAction($main);
	}

	/**
	 * @test
	 */
	public function editActionAssignsTheGivenMainToView()
	{
		$main = new \RWB\Rwbfeupload\Domain\Model\Main();

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$this->inject($this->subject, 'view', $view);
		$view->expects($this->once())->method('assign')->with('main', $main);

		$this->subject->editAction($main);
	}

	/**
	 * @test
	 */
	public function updateActionUpdatesTheGivenMainInMainRepository()
	{
		$main = new \RWB\Rwbfeupload\Domain\Model\Main();

		$mainRepository = $this->getMock('RWB\\Rwbfeupload\\Domain\\Repository\\MainRepository', array('update'), array(), '', FALSE);
		$mainRepository->expects($this->once())->method('update')->with($main);
		$this->inject($this->subject, 'mainRepository', $mainRepository);

		$this->subject->updateAction($main);
	}

	/**
	 * @test
	 */
	public function deleteActionRemovesTheGivenMainFromMainRepository()
	{
		$main = new \RWB\Rwbfeupload\Domain\Model\Main();

		$mainRepository = $this->getMock('RWB\\Rwbfeupload\\Domain\\Repository\\MainRepository', array('remove'), array(), '', FALSE);
		$mainRepository->expects($this->once())->method('remove')->with($main);
		$this->inject($this->subject, 'mainRepository', $mainRepository);

		$this->subject->deleteAction($main);
	}
}
