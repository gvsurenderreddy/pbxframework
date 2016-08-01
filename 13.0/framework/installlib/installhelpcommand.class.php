<?php
namespace FreePBX\Install;

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
class FreePBXHelpCommand extends HelpCommand {
	private $command;

	public function setCommand(FreePBXInstallCommand $command) {
		$this->command = $command;
	}
	protected function execute(InputInterface $input, OutputInterface $output) {
		$output->writeln(" _____   ______ _____   ____  __   __");
		$output->writeln("|  __ \ |  ____|  __ \ |  _  \\ \ / /");
		$output->writeln("| |__) || |___ | |__) || |_) | \ V /");
		$output->writeln("|  _  / |  ___||  ___/ |  _ <   > <");
		$output->writeln("| | \ \ | |    | |     | |_) | / . \\");
		$output->writeln("|_|  \_\|_|    |_|     |____/ /_/ \_\\");		

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
