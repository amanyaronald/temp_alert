<?php

namespace Sensy\Scrud\Traits;

use Livewire\WithFileUploads;

trait WizardTrait
{
    use WithFileUploads;

    public $target;
    public $item_id;

    public $_addMounted = false;

    // Steps
    public $step, $total_steps, $reached_step;
    // public $wizardSteps = []; // Use this in component

    /**
     * Go to a specific Step
     * @param int $step
     * @return void
     */
    public function specificStep($step)
    {
        $currentStep = $this->step;

        #validate current step
        $this->nextStep(request());
        //TODO: INDIVIDUAL VALIDATION OF STEPS

        #CHECK IF THERE ARE NO ERRORS
        if ($this->hasErrors()) {
            return $this->step = $currentStep;
        }

        if ($step <= $this->reached_step) {
            $this->step = $step;
        }
    }


    /**
     * Go to previous Step
     * @return void
     */
    public function previousStep()
    {
        if ($this->step > 1) {
            --$this->step;
        }
    }


    /**
     * Go to next Step
     * @return void
     */
    public function goNextStep()
    {
        if ($this->step < $this->total_steps) {
            $this->_preStep($this->step+1);
            ++$this->step;

            $this->reached_step++;
        }
    }

    /**
     * Initialize the Wizard
     */
    public function initializeWizard()
    {
        $this->step = 1;
        $this->reached_step = 1;
        $this->total_steps = count($this->wizardSteps);
    }

    public function _preStep($step)
    {
        switch ($step) {
            default:
                return 0;
//              return  $this->toast('warning', 'PENDING IMPLEMENTATION', 'This step is not yet implemented');
        }
    }

    /**
     * Check if there are any errors in the error bags.
     *
     * @return bool
     */
    protected function hasErrors()
    {
        return $this->getErrorBag()->isNotEmpty();
    }
}
