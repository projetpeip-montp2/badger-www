<?php 
    abstract class FormComponent
    {
        private $m_name;
        protected $m_isInParagraph;

        public function __construct($name)
        {
            $this->name($name);

            $this->m_isInParagraph = false;
        }

        public function name($name)
        {
            $this->m_name = $name;
        }

        public function getName()
        {
            return $this->m_name;
        }

        public function isInParagraph()
        {
            return $this->m_isInParagraph;
        }

        public abstract function getOutput();
    }



    abstract class FormComponentWithParagraph extends FormComponent
    {
        public function setIsInParagraph($isInParagraph)
        {
            $this->m_isInParagraph = $isInParagraph;
        }
    }



    class FormComponentSubmit extends FormComponentWithParagraph
    {
        public function __construct($name)
        {
            parent::__construct($name);

            $this->m_isInParagraph = false;
        }

        public function getOutput()
        {
            return '<input type="submit" value="' . $this->getName() . '" />';
        }
    }



    class FormComponentFielsetBegin extends FormComponent
    {
        public function __construct($name)
        {
            parent::__construct($name);

            $this->m_isInParagraph = false;
        }

        public function getOutput()
        {
            return '<fieldset>';
        }
    }



    class FormComponentFielsetEnd extends FormComponent
    {
        public function __construct($name)
        {
            parent::__construct($name);

            $this->m_isInParagraph = false;
        }

        public function getOutput()
        {
            return '</fieldset>';
        }
    }



    class FormComponentLegend extends FormComponent
    {
        public function __construct($name)
        {
            parent::__construct($name);

            $this->m_isInParagraph = false;
        }

        public function getOutput()
        {
            return '<legend>' . $this->getName() . '</legend>';
        }
    }



    class FormComponentHidden extends FormComponent
    {
        private $m_value;

        public function __construct($name)
        {
            parent::__construct($name);

            $this->m_isInParagraph = false;
        }

        public function value($value)
        {
            $this->m_value = $value;

            return $this;
        }

        public function getValue()
        {
            return $this->m_value;
        }

        public function getOutput()
        {
            return '<input type="hidden" name="' . $this->getName() . '" value="' . $this->getValue() . '" />';
        }
    }



    abstract class FormComponentWithLabel extends FormComponentWithParagraph
    {
        private $m_label = null;

        public function label($label)
        {
            $this->m_label = $label;

            return $this;
        }

        public function getLabel()
        {
            return $this->m_label;
        }
    }



    class FormComponentLabel extends FormComponentWithLabel
    {
        public function __construct($name)
        {
            parent::__construct($name);

            $this->m_isInParagraph = true;
        }

        public function getOutput()
        {
            return '<label>' . $this->getLabel() . '</label>';
        }
    }



    class FormComponentText extends FormComponentWithLabel
    {
        public function __construct($name)
        {
            parent::__construct($name);

            $this->m_isInParagraph = true;
        }

        public function getOutput()
        {
            $output = '';

            if($this->getLabel())
                $output .= '<label for="' . $this->getName() . '">' . $this->getLabel() . '</label>';

            $output .= '<input type="text" name="' . $this->getName() . '" id="' . $this->getName() . '"/>';

            return $output;
        }
    }



    class FormComponentCheckbox extends FormComponentWithLabel
    {
        public function __construct($name)
        {
            parent::__construct($name);

            $this->m_isInParagraph = true;
        }

        public function getOutput()
        {
            $output = '';

            $output .= '<input type="checkbox" name="' . $this->getName() . '" id="' . $this->getName() . '"/>';
            $output .= '<label for="' . $this->getName() . '">' . $this->getLabel() . '</label><br />';

            return $output;
        }
    }



    class FormComponentFile extends FormComponentWithLabel
    {
        public function __construct($name)
        {
            parent::__construct($name);

            $this->m_isInParagraph = true;
        }

        public function getOutput()
        {
            $output = '';

            if($this->getLabel())
                $output .= $this->getLabel();

            $output .= '<input type="file" name="' . $this->getName() . '"/>';

            return $output;
        }
    }



    abstract class FormComponentWithLabelAndChoices extends FormComponentWithLabel
    {
        private $m_choices = null;

        public function choices($choices)
        {
            $this->m_choices = $choices;

            return $this;
        }

        public function getChoices()
        {
            return $this->m_choices;
        }

    }



    class FormComponentRadiobox extends FormComponentWithLabelAndChoices
    {
        public function __construct($name)
        {
            parent::__construct($name);

            $this->m_isInParagraph = true;
        }

        public function getOutput()
        {
            $output = '';

            if($this->getLabel())
                $output .= $this->getLabel() . '<br />';

            foreach($this->getChoices() as $key => $value)
            {
                $output .= '<input type="radio" name="' . $this->getName() . '" id="' . $key . '" value="' . $key . '"/>';
                $output .= '<label for="' . $key . '">' . $value . '</label><br />';
            }

            return $output;
        }
    }



    class FormComponentSelect extends FormComponentWithLabelAndChoices
    {
        public function __construct($name)
        {
            parent::__construct($name);

            $this->m_isInParagraph = true;
        }

        public function getOutput()
        {
            $output = '';

            if($this->getLabel())
                $output .= '<label for="' . $this->getName() . '">' . $this->getLabel() . '</label><br />';

            $output .= '<select name="' . $this->getName() . '" id="' . $this->getName() . '">';

            foreach($this->getChoices() as $key => $value)
                $output .= '<option value="' . $key . '">' . $value . '</option>';

            $output .= '</select>';

            return $output;
        }
    }



    class Form
    {
        private $m_formComponents;
        private $m_action;
        private $m_method;
    
        private $m_sendFile;
        private $m_hasSubmit;
        private $m_fieldsetNumber;
    
        public function __construct($action, $method)
        {
            $this->m_formComponents = array();
            $this->setAction($action);
            $this->setMethod($method);

            $this->m_sendFile = false;
            $this->m_hasSubmit = false;
            $this->m_fieldsetNumber = 0;
        }

        public function setAction($action)
        {
            $this->m_action = $action;
        }

        public function setMethod($method)
        {
            $this->m_method = $method;
        }

        public function beginFieldset($name = '')
        {
            $this->m_formComponents[] = new FormComponentFielsetBegin('');

            if($name != '')
                $this->m_formComponents[] = new FormComponentLegend($name);

            $this->m_fieldsetNumber++;
        }

        public function endFieldset()
        {
            if($this->m_fieldsetNumber <= 0)
                throw new RuntimeException('No fieldset open');

            $this->m_formComponents[] = new FormComponentFielsetEnd('');

            $this->m_fieldsetNumber--;
        }

        public function add($type, $name)
        {
            $component;

            switch($type)
            {
                case 'checkbox':
                    $component = new FormComponentCheckBox($name);
                    break;

                case 'file':
                    $component = new FormComponentFile($name);
                    $this->m_sendFile = true;
                    break;

                case 'hidden':
                    $component = new FormComponentHidden($name);
                    break;

                case 'label':
                    $component = new FormComponentLabel($name);
                    break;

                case 'radiobox':
                    $component = new FormComponentRadioBox($name);
                    break;

                case 'select':
                    $component = new FormComponentSelect($name);
                    break;

                case 'submit':
                    $component = new FormComponentSubmit($name);
                    if($this->m_hasSubmit)
                        throw new RuntimeException('The form have already a submit input');

                    $this->m_hasSubmit = true;
                    break;

                case 'text':
                    $component = new FormComponentText($name);
                    break;

                default:
                    throw new RuntimeException('Unknown FormComponent : "' . $type . '"');
                    break;
            }

            $this->m_formComponents[] = $component;

            return $component;
        }

        public function toString()
        {
            if($this->m_fieldsetNumber > 0)
                throw new RuntimeException('A fieldset is always open');

            if(!$this->m_hasSubmit)
                throw new RuntimeException('No submit input in the form');

            $output = '';
            $output .= '<form action="' .  $this->m_action . '" method="' . $this->m_method . '"';

            if($this->m_sendFile)
                $output .= ' enctype="multipart/form-data"';

            $output .= '>';

            foreach($this->m_formComponents as $component)
            {
                if($component->isInParagraph())
                    $output .= '<p>';

                $output .= $component->getOutput();

                if($component->isInParagraph())
                    $output .= '</p>';
            }

            $output .= '</form>';

            return $output;
        }
    } 
?>
