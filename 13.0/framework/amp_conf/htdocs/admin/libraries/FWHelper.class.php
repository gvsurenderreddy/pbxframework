<?php
namespace FreePBX\Console\Application;

use Symfony\Component\Console\Command\HelpCommand;
use Symfony\Component\Console\Helper\DescriptorHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
/**
* HelpCommand displays the help for a given command.
*
* @author Fabien Potencier <fabien@symfony.com>
*/
class FWHelpCommand extends HelpCommand {
	private $command;

	public function setCommand($command) {
		$this->command = $command;
	}
	protected function execute(InputInterface $input, OutputInterface $output) {
		$output->writeln(" _____    ________  _____  ______   __");
		$output->writeln("|  __ \  |  ______||  __ \|  _ \ \ / /");
	  $output->writeln("| |__) | | |____   | |__) | |_) \ V /");
		$output->writeln("|  _  /  |  ____|  |  ___/|  _ < > <");
		$output->writeln("| | \ \  | |       | |    | |_) / . \\");
		$output->writeln("|_|  \_\ |_|       |_|    |____/_/ \_\\");

		if (null === $this->command) {
			$this->command = $this->getApplication()->find($input->getArgument('command_name'));
		}
		if ($input->getOption('xml')) {
			$input->setOption('format', 'xml');
		}
		$helper = new DescriptorHelper();
		$helper->describe($output, $this->command, array(
			'format' => $input->getOption('format'),
			'raw' => $input->getOption('raw'),
		));
		$this->command = null;
	}
}
