<?php
	/**
	 * This is the abstract Form class for the Create, Edit, and Delete functionality
	 * of the Parameter class.  This code-generated class
	 * contains all the basic Qform elements to display an HTML form that can
	 * manipulate a single Parameter object.
	 *
	 * To take advantage of some (or all) of these control objects, you
	 * must create a new Form which extends this ParameterEditFormBase
	 * class.
	 *
	 * Any and all changes to this file will be overwritten with any subsequent re-
	 * code generation.
	 * 
	 * @package My Application
	 * @subpackage FormBaseObjects
	 * 
	 */
	abstract class ParameterEditFormBase extends QForm {
		// General Form Variables
		protected $objParameter;
		protected $strTitleVerb;
		protected $blnEditMode;

		// Controls for Parameter's Data Fields
		protected $lblId;
		protected $lstOperation;
		protected $txtOrderNumber;
		protected $lstVariable;
		protected $chkReferenceFlag;

		// Other ListBoxes (if applicable) via Unique ReverseReferences and ManyToMany References

		// Button Actions
		protected $btnSave;
		protected $btnCancel;
		protected $btnDelete;

		protected function SetupParameter() {
			// Lookup Object PK information from Query String (if applicable)
			// Set mode to Edit or New depending on what's found
			$intId = QApplication::QueryString('intId');
			if (($intId)) {
				$this->objParameter = Parameter::Load(($intId));

				if (!$this->objParameter)
					throw new Exception('Could not find a Parameter object with PK arguments: ' . $intId);

				$this->strTitleVerb = QApplication::Translate('Edit');
				$this->blnEditMode = true;
			} else {
				$this->objParameter = new Parameter();
				$this->strTitleVerb = QApplication::Translate('Create');
				$this->blnEditMode = false;
			}
		}

		protected function Form_Create() {
			// Call SetupParameter to either Load/Edit Existing or Create New
			$this->SetupParameter();

			// Create/Setup Controls for Parameter's Data Fields
			$this->lblId_Create();
			$this->lstOperation_Create();
			$this->txtOrderNumber_Create();
			$this->lstVariable_Create();
			$this->chkReferenceFlag_Create();

			// Create/Setup ListBoxes (if applicable) via Unique ReverseReferences and ManyToMany References

			// Create/Setup Button Action controls
			$this->btnSave_Create();
			$this->btnCancel_Create();
			$this->btnDelete_Create();
		}

		// Protected Create Methods
		// Create and Setup lblId
		protected function lblId_Create() {
			$this->lblId = new QLabel($this);
			$this->lblId->Name = QApplication::Translate('Id');
			if ($this->blnEditMode)
				$this->lblId->Text = $this->objParameter->Id;
			else
				$this->lblId->Text = 'N/A';
		}

		// Create and Setup lstOperation
		protected function lstOperation_Create() {
			$this->lstOperation = new QListBox($this);
			$this->lstOperation->Name = QApplication::Translate('Operation');
			$this->lstOperation->Required = true;
			if (!$this->blnEditMode)
				$this->lstOperation->AddItem(QApplication::Translate('- Select One -'), null);
			$objOperationArray = Operation::LoadAll();
			if ($objOperationArray) foreach ($objOperationArray as $objOperation) {
				$objListItem = new QListItem($objOperation->__toString(), $objOperation->Id);
				if (($this->objParameter->Operation) && ($this->objParameter->Operation->Id == $objOperation->Id))
					$objListItem->Selected = true;
				$this->lstOperation->AddItem($objListItem);
			}
		}

		// Create and Setup txtOrderNumber
		protected function txtOrderNumber_Create() {
			$this->txtOrderNumber = new QIntegerTextBox($this);
			$this->txtOrderNumber->Name = QApplication::Translate('Order Number');
			$this->txtOrderNumber->Text = $this->objParameter->OrderNumber;
		}

		// Create and Setup lstVariable
		protected function lstVariable_Create() {
			$this->lstVariable = new QListBox($this);
			$this->lstVariable->Name = QApplication::Translate('Variable');
			$this->lstVariable->Required = true;
			if (!$this->blnEditMode)
				$this->lstVariable->AddItem(QApplication::Translate('- Select One -'), null);
			$objVariableArray = Variable::LoadAll();
			if ($objVariableArray) foreach ($objVariableArray as $objVariable) {
				$objListItem = new QListItem($objVariable->__toString(), $objVariable->Id);
				if (($this->objParameter->Variable) && ($this->objParameter->Variable->Id == $objVariable->Id))
					$objListItem->Selected = true;
				$this->lstVariable->AddItem($objListItem);
			}
		}

		// Create and Setup chkReferenceFlag
		protected function chkReferenceFlag_Create() {
			$this->chkReferenceFlag = new QCheckBox($this);
			$this->chkReferenceFlag->Name = QApplication::Translate('Reference Flag');
			$this->chkReferenceFlag->Checked = $this->objParameter->ReferenceFlag;
		}


		// Setup btnSave
		protected function btnSave_Create() {
			$this->btnSave = new QButton($this);
			$this->btnSave->Text = QApplication::Translate('Save');
			$this->btnSave->AddAction(new QClickEvent(), new QServerAction('btnSave_Click'));
			$this->btnSave->PrimaryButton = true;
			$this->btnSave->CausesValidation = true;
		}

		// Setup btnCancel
		protected function btnCancel_Create() {
			$this->btnCancel = new QButton($this);
			$this->btnCancel->Text = QApplication::Translate('Cancel');
			$this->btnCancel->AddAction(new QClickEvent(), new QServerAction('btnCancel_Click'));
			$this->btnCancel->CausesValidation = false;
		}

		// Setup btnDelete
		protected function btnDelete_Create() {
			$this->btnDelete = new QButton($this);
			$this->btnDelete->Text = QApplication::Translate('Delete');
			$this->btnDelete->AddAction(new QClickEvent(), new QConfirmAction(sprintf(QApplication::Translate('Are you SURE you want to DELETE this %s?'), 'Parameter')));
			$this->btnDelete->AddAction(new QClickEvent(), new QServerAction('btnDelete_Click'));
			$this->btnDelete->CausesValidation = false;
			if (!$this->blnEditMode)
				$this->btnDelete->Visible = false;
		}
		
		// Protected Update Methods
		protected function UpdateParameterFields() {
			$this->objParameter->OperationId = $this->lstOperation->SelectedValue;
			$this->objParameter->OrderNumber = $this->txtOrderNumber->Text;
			$this->objParameter->VariableId = $this->lstVariable->SelectedValue;
			$this->objParameter->ReferenceFlag = $this->chkReferenceFlag->Checked;
		}


		// Control ServerActions
		protected function btnSave_Click($strFormId, $strControlId, $strParameter) {
			$this->UpdateParameterFields();
			$this->objParameter->Save();


			$this->RedirectToListPage();
		}

		protected function btnCancel_Click($strFormId, $strControlId, $strParameter) {
			$this->RedirectToListPage();
		}

		protected function btnDelete_Click($strFormId, $strControlId, $strParameter) {

			$this->objParameter->Delete();

			$this->RedirectToListPage();
		}
		
		protected function RedirectToListPage() {
			QApplication::Redirect('parameter_list.php');
		}
	}
?>