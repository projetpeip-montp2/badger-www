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

        public function isInParagraph($isInParagraph)
        {
            $this->m_isInParagraph = $isInParagraph;
        }

        public function getIsInParagraph()
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
        private $m_onClick;

        public function __construct($name)
        {
            parent::__construct($name);

            $this->m_isInParagraph = false;

            $this->m_onClick = '';
        }

        public function onClick($onClick)
        {
            $this->m_onClick = $onClick;
        }

        public function getOutput()
        {
            $output = '<input type="submit" value="' . $this->getName() . '"';

            if($this->m_onClick)
                $output .= ' onclick="' . $this->m_onClick . '"';

            $output .= '/>';

            return $output;
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
        private $m_placeHolder;
        private $m_value;
        private $m_size;

        public function __construct($name)
        {
            parent::__construct($name);

            $this->m_isInParagraph = true;

            $this->m_placeHolder = '';
            $this->m_value = '';
            $this->m_value = '50';
        }

        public function placeHolder($placeHolder)
        {
            $this->m_placeHolder = $placeHolder;

            return $this;
        }

        public function getPlaceHolder()
        {
            return $this->m_placeHolder;
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

        public function size($size)
        {
            $this->m_size = $size;

            return $this;
        }

        public function getSize()
        {
            return $this->m_size;
        }

        public function getOutput()
        {
            $output = '';

            if($this->getLabel())
                $output .= '<label for="' . $this->getName() . '">' . $this->getLabel() . '</label>';

            $output .= '<input type="text" name="' . $this->getName() . 
                                        '" id="' . $this->getName() . 
                                        '" placeholder="' . $this->getPlaceHolder() . 
                                        '" value="' . $this->getValue() .
                                        '" size="' . $this->getSize() .  '"/>';

            return $output;
        }
    }

    class FormComponentTextArea extends FormComponentWithLabel
    {
        private $m_text;
        private $m_rows;
        private $m_cols;

        public function __construct($name)
        {
            parent::__construct($name);

            $this->m_isInParagraph = true;

            $this->m_text = '';
            $this->m_rows = 3;
            $this->m_cols = 22;
        }

        public function rows($rows)
        {
            $this->m_rows = $rows;

            return $this;
        }

        public function getRows()
        {
            return $this->m_rows;
        }

        public function cols($cols)
        {
            $this->m_cols = $cols;

            return $this;
        }

        public function getCols()
        {
            return $this->m_cols;
        }

        public function text($text)
        {
            $this->m_text = $text;

            return $this;
        }

        public function getText()
        {
            return $this->m_text;
        }

        public function getOutput()
        {
            $output = '';

            if($this->getLabel())
                $output .= '<label for="' . $this->getName() . '">' . $this->getLabel() . '</label>';

            $output .= '<textarea name="' . $this->getName() . 
                               '" id="' . $this->getName() . 
                               '" rows="' . $this->getRows() . 
                               '" cols="' . $this->getCols() . '">' . $this->getText() .'</textarea>';

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

            $output .= "\t" . '<input type="checkbox" name="' . $this->getName() . '" id="' . $this->getName() . '"/>';
            $output .= '<label for="' . $this->getName() . '">' . $this->getLabel() . '</label>';

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
        private $m_selected = null;

        public function choices($choices)
        {
            $this->m_choices = $choices;

            return $this;
        }

        public function getChoices()
        {
            return $this->m_choices;
        }

        public function selected($selected)
        {
            $this->m_selected = $selected;

            return $this;
        }

        public function getSelected()
        {
            return $this->m_selected;
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

            $output .= '<select name="' . $this->getName() . '" id="' . $this->getName() . '">' . "\n";

            foreach($this->getChoices() as $key => $value)
            {
                $output .= '<option value="' . $key . '"';
                if($value == $this->getSelected())
                    $output .= '" selected';
                $output .= '>' . $value . '</option>' . "\n";
            }

            $output .= '</select>';

            return $output;
        }
    }



    class Form
    {
        private $m_formComponents;
        private $m_action;
        private $m_method;

        private $m_id;
    
        private $m_sendFile;
        private $m_hasSubmit;
        private $m_fieldsetNumber;
    
        public function __construct($action, $method)
        {
            $this->m_formComponents = array();
            $this->setAction($action);
            $this->setMethod($method);

            $this->m_id = '';
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

        public function setId($id)
        {
            $this->m_id = $id;
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

                case 'textarea':
                    $component = new FormComponentTextArea($name);
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

            if($this->m_id)
                $output .= ' id="' . $this->m_id . '"';

            $output .= '>' . "\n";

            foreach($this->m_formComponents as $component)
            {
                if($component->getIsInParagraph())
                    $output .= '<p>';

                $output .= $component->getOutput();

                if($component->getIsInParagraph())
                    $output .= '</p>'  . "\n";
            }

            $output .= "\n" . '</form>';

            return $output;
        }

        public function toTr()
        {
            $this->disableParagraphs();

            if($this->m_fieldsetNumber > 0)
                throw new RuntimeException('A fieldset is always open');
            if(!$this->m_hasSubmit)
                throw new RuntimeException('No submit input in the form');

            $output = '';
            $output .= '<form action="' .  $this->m_action . '" method="' . $this->m_method . '">';
            $output .= '<tr>';

            foreach($this->m_formComponents as $component)
                $output .= "\n" . '<td>' . $component->getOutput() . '</td>';

            $output .= "\n" . '</tr></form>';

            return $output;
        }

        public function disableParagraphs()
        {
            foreach($this->m_formComponents as $component)
                $component->isInParagraph(false);
        }
    } 
?>
