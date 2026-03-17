# ModulesGarden Framework Documentation

This documentation describes the patterns and structure used in ModulesGarden Framework. Use this as a reference to create new WHMCS modules.

## Table of Contents
1. [Quick Start Guide](#quick-start-guide)
2. [Module Structure](#module-structure)
3. [HTTP Controllers](#http-controllers)
4. [Models](#models)
5. [UI Components](#ui-components)
   - [Accordion](#accordion)
   - [AccordionElement](#accordionelement)
   - [AdaptiveTwoColumnsContainer](#adaptivetwo-columnscontainer)
   - [Alert](#alert)
   - [AppBreadcrumb](#appbreadcrumb)
   - [AppFooter](#appfooter)
   - [AppNavBar](#appnavbar)
   - [ArrayTreeView](#arraytreeview)
   - [ArrayTreeViewItem](#arraytreeviewitem)
   - [Badge](#badge)
   - [BaseSection](#basesection)
   - [BlockError](#blockerror)
   - [BlockZeroData](#blockzerodata)
   - [Board](#board)
   - [BoardColumn](#boardcolumn)
   - [BoardItem](#boarditem)
   - [Button](#button)
   - [Card](#card)
   - [ChangelogModal](#changelogmodal)
   - [Checkbox](#checkbox)
   - [ColorPicker](#colorpicker)
   - [ColorPreview](#colorpreview)
   - [Container](#container)
   - [ContainerInline](#containerinline)
   - [CopyPasswordInline](#copypasswordinline)
   - [CopyTextInline](#copytextinline)
   - [CopyToClipboardButton](#copytoclipboardbutton)
   - [CustomErrorPage](#customerrorpage)
   - [DataTable](#datatable)
   - [DatePicker](#datepicker)
   - [Div](#div)
   - [Dropdown](#dropdown)
   - [DropdownMenu](#dropdownmenu)
   - [DropdownMenuItem](#dropdownmenuitem)
   - [ElementsList](#elementslist)
   - [FileManager](#filemanager)
   - [Form](#form)
   - [FormGroup](#formgroup)
   - [FormInputEmail](#forminputemail)
   - [FormInputFile](#forminputfile)
   - [FormInputGroup](#forminputgroup)
   - [FormInputGroupLabel](#forminputgrouplabel)
   - [FormInputLabel](#forminputlabel)
   - [FormInputPassword](#forminputpassword)
   - [FormInputText](#forminputtext)
   - [FormInputTextCopy](#forminputtextcopy)
   - [FormLabel](#formlabel)
   - [FormPasswordGenerator](#formpasswordgenerator)
   - [FormWizard](#formwizard)
   - [FormWizardStep](#formwizardstep)
   - [Graph](#graph)
   - [Grid](#grid)
   - [HiddenField](#hiddenfield)
   - [Hint](#hint)
   - [HintsBox](#hintsbox)
   - [HtmlEditor](#htmleditor)
   - [Icon](#icon)
   - [IconButton](#iconbutton)
   - [IconText](#icontext)
   - [Iframe](#iframe)
   - [Image](#image)
   - [ImagePicker](#imagepicker)
   - [ImageSelector](#imageselector)
   - [ImageText](#imagetext)
   - [Label](#label)
   - [LayoutWithSidebar](#layoutwithsidebar)
   - [Link](#link)
   - [ListInfo](#listinfo)
   - [ListSimple](#listsimple)
   - [MarkdownEditor](#markdowneditor)
   - [MediaLibrary](#medialibrary)
   - [MediaLibraryItem](#medialibraryitem)
   - [Modal](#modal)
   - [ModulesGardenConnectionButton](#modulesgardenconnectionbutton)
   - [NavBar](#navbar)
   - [NavBarItem](#navbaritem)
   - [News](#news)
   - [NotificationDropdown](#notificationdropdown)
   - [NotificationDropdownItem](#notificationdropdownitem)
   - [Number](#number)
   - [OverlayComponents](#overlaycomponents)
   - [PageDescription](#pagedescription)
   - [PageNavBar](#pagenavbar)
   - [PageNotFound](#pagenotfound)
   - [PageViewWidget](#pageviewwidget)
   - [PreBlock](#preblock)
   - [PreBlockArrayPrint](#preblockarrayprint)
   - [ProgressBar](#progressbar)
   - [RadioButton](#radiobutton)
   - [RandomStringGeneratorButton](#randomstringgeneratorbutton)
   - [Row](#row)
   - [RowFluid](#rowfluid)
   - [ServicePricingWidget](#servicepricingwidget)
   - [Sidebar](#sidebar)
   - [SidebarItem](#sidebaritem)
   - [Slider](#slider)
   - [SliderMarks](#slidermarks)
   - [Span](#span)
   - [Switcher](#switcher)
   - [Tab](#tab)
   - [TableSimple](#tablesimple)
   - [TabsWidget](#tabswidget)
   - [Tagger](#tagger)
   - [Text](#text)
   - [TextArea](#textarea)
   - [TextParagraph](#textparagraph)
   - [TextShowHide](#textshowhide)
   - [TextSmall](#textsmall)
   - [TicketReplyPreview](#ticketreplypreview)
   - [TileButton](#tilebutton)
   - [TilesBar](#tilesbar)
   - [Toolbar](#toolbar)
   - [Tooltip](#tooltip)
   - [TreeListContainer](#treelistcontainer)
   - [TreeListItem](#treelistitem)
   - [TreeListSubItem](#treelistsubitem)
   - [UploadField](#uploadfield)
   - [UsageWidget](#usagewidget)
   - [VisibilityWrapper](#visibilitywrapper)
   - [VncConsole](#vncconsole)
   - [Widget](#widget)
   - [XtermjsConsole](#xtermjsconsole)
6. [Actions](#actions)
7. [Best Practices](#best-practices)

## Quick Start Guide

Below you'll find a quick introduction that allows you to build an `addon module` or `provisioning module` without diving into how the entire system works.
Detailed information can be found in specific sections linked in the sidebar.

### PHP Documentation
You can find it here: http://whmcs-products.internal.modulesgarden.com/module-framework/

### Setting Up Module Skeleton
1. Create a directory with your module name. For example `modules/servers/YourModule` or `modules/addons/YourModule`.
2. Create a composer.json file
```json
{
   "repositories":[
      {
         "type":"vcs",
         "url":"git@git.mglocal:whmcs-products/module-framework.git"
      }
   ],
   "require":{
      "modulesgarden/whmcs-framework":"*"
   },
  "scripts": {
    "fw-install": [
      "\\ModulesGarden\\OpenStackVpsCloud\\Install\\Installer::run"
    ]
  },
  "autoload": {
    "psr-4": {
      "ModulesGarden\\YOUR_MODULE_NAME\\Core\\": "./core",
      "ModulesGarden\\YOUR_MODULE_NAME\\App\\": "./app",
      "ModulesGarden\\YOUR_MODULE_NAME\\Packages\\": "./packages",
      "ModulesGarden\\YOUR_MODULE_NAME\\Fragments\\": "./fragments",
      "ModulesGarden\\YOUR_MODULE_NAME\\Components\\": "./components",
      "ModulesGarden\\YOUR_MODULE_NAME\\Install\\": "./install"
    }
  }
}
```
3. Run the command `composer install`. You can also right-click on the `composer.json` file in PhpStorm and select `Composer` -> `Install`.
4. Run the command `composer run-script fw-install`, which will copy files and update namespaces.
5. Upload the file to the main WHMCS directory
6. Activate the `YourModule` module
7. Congratulations! You've configured the default framework configuration. There are many things there that you don't need and that you must remove before delivering the module, but we'll deal with that soon ;)

### Default Package Configuration
The basic framework configuration contains a large number of packages that are used for framework development and testing.
There are also many examples that you can use in your application.
Let's start by disabling packages that you don't need.
You can do this in the file `modules/servers/YourModule/app/Config/packages.yml` by changing the value from `true` to `false` or simply removing the line.
An example configuration file might look like this:
```yml
AccessControl: true
Samples: true
Logs: true
ModuleSettings: true
RequirementsChecker: true
```
Initially, I suggest turning off everything except "Samples", which contains sample UI elements that you'll use later to build your application.

### First Controller
To start, you need to set up the default content for the `Home` controller, which is loaded when no parameters are provided in the URL.
It's located in the `app/Http/Admin` directory and looks something like this:
```php
<?php

namespace ModulesGarden\YourModule\App\Http\Admin;

use ModulesGarden\YourModule\Core\Http\AbstractController;
use function ModulesGarden\YourModule\Core\Helper\view;

class Home extends AbstractController
{
    public function index()
    {
        return view();
    }
}
```
What interests us is the `index` method which is the default method of each controller.
This method is executed when no specific controller method is specified in the URL.
Unfortunately, even though we have a controller, it still doesn't do anything.

To display UI elements, you first need to add such an element. For this purpose, I'll use the `Alert` component:
```php
<?php

namespace ModulesGarden\YourModule\App\Http\Admin;

use ModulesGarden\YourModule\Components\Alert\AlertSuccess;
use ModulesGarden\YourModule\Core\Http\AbstractController;
use function ModulesGarden\YourModule\Core\Helper\view;

class Home extends AbstractController
{
    public function index()
    {
        $alert = new AlertSuccess();
        $alert->setText('First controller!');

        return view()
            ->addElement($alert);
    }
}
```

We managed to display something, but the presented method is far from how we should build the module UI and should be avoided.
In the next chapter, you'll learn how to properly build UI.

### UI Components

UI elements should be placed in the `app/UI/Admin` or `app/UI/Client` directory. If a given element is available for both client and admin, it's worth placing it in a separate `app/UI/Shared` directory. A given page can have many UI elements, so we'll add three additional directories that tell us about:
- the controller name that uses the given UI element
- the controller method that loads the given UI element
- the type of component we're using

Our first element will be available only in the administrator view and will be a simple Widget, so we'll create it in the `app/UI/Admin/Home/Index/Widgets` directory:
```php
<?php

namespace ModulesGarden\YourModule\App\UI\Admin\Home\Index\Widgets;

use ModulesGarden\YourModule\Components\Widget\Widget;

class Test extends Widget
{

}
```
Next, you need to load the created element in the controller, which after modification will look like this:
```php
<?php

namespace ModulesGarden\YourModule\App\Http\Admin;

use ModulesGarden\YourModule\App\UI\Admin\Home\Index\Widgets\Test;
use ModulesGarden\YourModule\Core\Http\AbstractController;
use function ModulesGarden\YourModule\Core\Helper\view;

class Home extends AbstractController
{
    public function index()
    {
        return view()
            ->addElement(Test::class);
    }
}
```
We specifically don't create the object ourselves here but let the framework do it. Thanks to this, the controller code itself is much cleaner.
After introducing changes, not much happens in our module again.

We return to the Widget we created earlier and implement the `loadHtml` method, which is responsible for building the appearance of the given element.
We set the title of the element and add Alert type components:
```php
<?php

namespace ModulesGarden\YourModule\App\UI\Admin\Home\Index\Widgets;

use ModulesGarden\YourModule\Components\Alert\AlertDanger;
use ModulesGarden\YourModule\Components\Widget\Widget;

class Test extends Widget
{
    public function loadHtml(): void
    {
        $this->setTitle('First widget');

        $alert = new AlertDanger();
        $alert->setText('Alert in widget!');
        $this->addElement($alert);
    }
}
```

### Ajax UI Components

If what we want to display requires operations that may take a longer time, or if we want to refresh our UI element (automatically or cyclically), we must use the `loadData` method and implement the `AjaxOnLoad` interface:
```php
<?php

namespace ModulesGarden\YourModule\App\UI\Admin\Home\Index\Widgets;

use ModulesGarden\YourModule\Components\Alert\AlertDanger;
use ModulesGarden\YourModule\Components\Widget\Widget;
use ModulesGarden\YourModule\Core\Contracts\Components\AjaxOnLoad;

class Test extends Widget implements AjaxOnLoad
{
    public function loadData(): void
    {
        $this->setTitle('First widget');

        $alert = new AlertDanger();
        $alert->setText('Alert in widget!');
        $this->addElement($alert);
    }
}
```
Going further, we can also force auto-refresh on our element every one second (1000 ms):
```php
<?php

namespace ModulesGarden\YourModule\App\UI\Admin\Home\Index\Widgets;

use ModulesGarden\YourModule\Components\Alert\AlertDanger;
use ModulesGarden\YourModule\Components\Widget\Widget;
use ModulesGarden\YourModule\Core\Contracts\Components\AjaxAutoReload;
use ModulesGarden\YourModule\Core\Contracts\Components\AjaxOnLoad;

class Test extends Widget implements AjaxOnLoad, AjaxAutoReload
{
    protected int $ajaxAutoReloadInterval = 1000;

    public function loadData(): void
    {
        $this->setTitle('First widget');

        $alert = new AlertDanger();
        $alert->setText('Alert in widget! Current timestamp: ' . time());
        $this->addElement($alert);
    }
}
```
You probably already noticed that after refreshing the page, our widget has no content. This is because we didn't set the initial content using the `loadHtml` method. So let's implement it again:

```php
<?php

namespace ModulesGarden\YourModule\App\UI\Admin\Home\Index\Widgets;

use ModulesGarden\YourModule\Components\Alert\AlertDanger;
use ModulesGarden\YourModule\Components\Widget\Widget;
use ModulesGarden\YourModule\Core\Contracts\Components\AjaxAutoReload;
use ModulesGarden\YourModule\Core\Contracts\Components\AjaxOnLoad;

class Test extends Widget implements AjaxOnLoad, AjaxAutoReload
{
    protected int $ajaxAutoReloadInterval = 1000;

    public function loadHtml(): void
    {
        $this->setTitle('First widget');
        $this->setContent('Loading...');
    }

    public function loadData(): void
    {
        $alert = new AlertDanger();
        $alert->setText('Alert in widget! Current timestamp: ' . time());
        $this->addElement($alert);
    }
}
```

### Component Structure

UI Components in ModulesGarden Framework extend `AbstractComponent` and implement specific interfaces based on their area (Admin/Client) and functionality.

**Basic UI Component Pattern:**
```php
<?php

namespace ModulesGarden\YourModule\App\UI\Admin\YourSection\Pages;

use ModulesGarden\YourModule\Core\Components\AbstractComponent;
use ModulesGarden\YourModule\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\YourModule\Core\Contracts\Components\AjaxComponentInterface;

class YourComponent extends AbstractComponent implements AdminAreaInterface, AjaxComponentInterface
{
    public function loadHtml(): void
    {
        // Component initialization logic
        $this->setTitle($this->translate('title'));
        
        // Add child components, configure properties, etc.
    }

    public function loadData(): void
    {
        // Data loading logic (for AJAX components)
    }
}
```

### Container Components

Containers are used to group and organize multiple UI components together.

**Container Pattern with Multiple Components:**
```php
<?php

namespace ModulesGarden\YourModule\App\UI\Admin\BasicElements;

use ModulesGarden\YourModule\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\YourModule\Components\Container\Container;

class BasicElementsContainer extends Container implements AdminAreaInterface
{
    public function loadHtml(): void
    {
        $this->addElement(new Alerts\AlertsWidget());
        $this->addElement(new Labels\LabelsWidget());
        $this->addElement(new Buttons\ButtonsWidget());
        $this->addElement(new Modals\ModalsWidget());
    }
}
```

### Alert Components

Alert components display important messages to users with different severity levels.

**Alerts Widget with All Alert Types:**
```php
<?php

namespace ModulesGarden\YourModule\App\UI\Admin\BasicElements\Alerts;

use ModulesGarden\YourModule\Components\Alert\AlertDanger;
use ModulesGarden\YourModule\Components\Alert\AlertInfo;
use ModulesGarden\YourModule\Components\Alert\AlertSuccess;
use ModulesGarden\YourModule\Components\Alert\AlertWarning;
use ModulesGarden\YourModule\Components\Widget\Widget;
use ModulesGarden\YourModule\Core\Contracts\Components\AdminAreaInterface;

class AlertsWidget extends Widget implements AdminAreaInterface
{
    public function loadHtml(): void
    {
        $this->setTitle('Alerts');

        // Basic alerts
        $this->addElement((new AlertDanger())->setText('Alert Danger'));
        $this->addElement((new AlertInfo())->setText('Alert Info'));
        $this->addElement((new AlertSuccess())->setText('Alert Success'));
        $this->addElement((new AlertWarning())->setText('Alert Warning'));

        // Outline style alerts
        $this->addElement((new AlertDanger())->setText($this->translate('Alert Danger with outline'))->setOutline());
        $this->addElement((new AlertInfo())->setText('Alert Info with outline')->setOutline());
        $this->addElement((new AlertSuccess())->setText('Alert Success with outline')->setOutline());
        $this->addElement((new AlertWarning())->setText('Alert Warning with outline')->setOutline());

        // Dismissible alerts
        $this->addElement((new AlertDanger())->setText('Alert Danger with dismiss button')->showDismissButton());
        $this->addElement((new AlertInfo())->setText('Alert Info with dismiss button')->showDismissButton());
        $this->addElement((new AlertSuccess())->setText('Alert Success with dismiss button')->showDismissButton());
        $this->addElement((new AlertWarning())->setText('Alert Warning with dismiss button')->showDismissButton());

        // Combined outline and dismissible alerts
        $this->addElement((new AlertDanger())->setText('Alert Danger with outline and dismiss button')->setOutline()->showDismissButton());
        $this->addElement((new AlertInfo())->setText('Alert Info with outline and dismiss button')->setOutline()->showDismissButton());
        $this->addElement((new AlertSuccess())->setText('Alert Success with outline and dismiss button')->setOutline()->showDismissButton());
        $this->addElement((new AlertWarning())->setText('Alert Warning with outline and dismiss button')->setOutline()->showDismissButton());
    }
}
```

### Label and Badge Components

Labels and badges are used to display status information and categorize content.

**Labels Widget with Different Types:**
```php
<?php

namespace ModulesGarden\YourModule\App\UI\Admin\BasicElements\Labels;

use ModulesGarden\YourModule\Components\Label\Label;
use ModulesGarden\YourModule\Components\Label\LabelDanger;
use ModulesGarden\YourModule\Components\Label\LabelInfo;
use ModulesGarden\YourModule\Components\Label\LabelPrimary;
use ModulesGarden\YourModule\Components\Label\LabelSecondary;
use ModulesGarden\YourModule\Components\Label\LabelSuccess;
use ModulesGarden\YourModule\Components\Label\LabelWarning;
use ModulesGarden\YourModule\Components\Toolbar\Toolbar;
use ModulesGarden\YourModule\Components\Widget\Widget;
use ModulesGarden\YourModule\Core\Contracts\Components\AdminAreaInterface;

class LabelsWidget extends Widget implements AdminAreaInterface
{
    public function loadHtml(): void
    {
        $this->setTitle('Labels');

        $toolbar = new Toolbar();
        
        // Standard labels
        $toolbar->addElement((new LabelWarning())->setText('Label Warning'));
        $toolbar->addElement((new LabelSuccess())->setText('Label Success'));
        $toolbar->addElement((new LabelDanger())->setText('Label Danger'));
        $toolbar->addElement((new LabelInfo())->setText('Label Info'));
        $toolbar->addElement((new LabelPrimary())->setText('Label Primary'));
        $toolbar->addElement((new LabelSecondary())->setText('Label Secondary'));
        $toolbar->addElement((new Label())->setText('Label Default'));

        // Status labels (larger size for status display)
        $toolbar->addElement((new LabelWarning())->setText('Label Warning')->displayAsStatusLabel());
        $toolbar->addElement((new LabelSuccess())->setText('Label Success')->displayAsStatusLabel());
        $toolbar->addElement((new LabelDanger())->setText('Label Danger')->displayAsStatusLabel());
        $toolbar->addElement((new LabelInfo())->setText('Label Info')->displayAsStatusLabel());
        $toolbar->addElement((new LabelPrimary())->setText('Label Primary')->displayAsStatusLabel());
        $toolbar->addElement((new LabelSecondary())->setText('Label Secondary')->displayAsStatusLabel());
        $toolbar->addElement((new Label())->setText('Label Default')->displayAsStatusLabel());

        $this->addElement($toolbar);
    }
}
```

### Button Components

Buttons handle user interactions and come in various styles and configurations.

**Buttons Widget with Icons and Actions:**
```php
<?php

namespace ModulesGarden\YourModule\App\UI\Admin\BasicElements\Buttons;

use ModulesGarden\YourModule\Components\Button\ButtonDanger;
use ModulesGarden\YourModule\Components\Button\ButtonInfo;
use ModulesGarden\YourModule\Components\Button\ButtonPrimary;
use ModulesGarden\YourModule\Components\Button\ButtonSuccess;
use ModulesGarden\YourModule\Components\Button\ButtonWarning;
use ModulesGarden\YourModule\Components\Toolbar\Toolbar;
use ModulesGarden\YourModule\Components\Widget\Widget;
use ModulesGarden\YourModule\Core\Contracts\Components\AdminAreaInterface;

class ButtonsWidget extends Widget implements AdminAreaInterface
{
    public function loadHtml(): void
    {
        $this->setTitle('Buttons');

        $toolbar = new Toolbar();

        // Basic buttons
        $toolbar->addElement((new ButtonSuccess())->setTitle('Button Success'));
        $toolbar->addElement((new ButtonDanger())->setTitle('Button Danger'));
        $toolbar->addElement((new ButtonWarning())->setTitle('Button Warning'));
        $toolbar->addElement((new ButtonInfo())->setTitle('Button Info'));
        $toolbar->addElement((new ButtonPrimary())->setTitle('Button Primary'));

        // Buttons with icons
        $toolbar->addElement((new ButtonSuccess())->setTitle('Button Success')->setIcon('plus'));
        $toolbar->addElement((new ButtonDanger())->setTitle('Button Danger')->setIcon('trash'));
        $toolbar->addElement((new ButtonWarning())->setTitle('Button Warning')->setIcon('warning'));
        $toolbar->addElement((new ButtonInfo())->setTitle('Button Info')->setIcon('info'));
        $toolbar->addElement((new ButtonPrimary())->setTitle('Button Primary')->setIcon('edit'));

        $this->addElement($toolbar);
    }
}
```

### DataTable Components

DataTables are powerful components for displaying tabular data with search, sort, and pagination capabilities.

**DataTable with Query Data Provider:**
```php
<?php

namespace ModulesGarden\YourModule\App\UI\Admin\DataTable;

use ModulesGarden\YourModule\Components\DataTable\Column;
use ModulesGarden\YourModule\Components\DataTable\DataTable;
use ModulesGarden\YourModule\Components\IconButton\IconButton;
use ModulesGarden\YourModule\Components\VisibilityWrapper\VisibilityWrapper;
use ModulesGarden\YourModule\Core\Components\Actions\RedirectFromParam;
use ModulesGarden\YourModule\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\YourModule\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\YourModule\Core\DataProviders\QueryDataProvider;
use WHMCS\User\Client;

class ClientsDataTable extends DataTable implements AdminAreaInterface, AjaxComponentInterface
{
    public function loadHtml(): void
    {
        $this->setTitle('Clients');

        // Define searchable and sortable columns
        $this->addColumn((new Column('id'))
                ->setSortable()
                ->setSearchable(true, Column::TYPE_INT))
            ->addColumn((new Column('firstname'))
                ->setSortable(true)
                ->setSearchable(true))
            ->addColumn((new Column('lastname'))
                ->setSortable(true)
                ->setSearchable(true))
            ->addColumn((new Column('email'))
                ->setSortable(true)
                ->setSearchable(true))
            ->addColumn((new Column('status')));

        // Add action buttons with conditional visibility
        $redirectButton = new IconButton();
        $redirectButton->setIcon('account');
        $redirectButton->onClick(new RedirectFromParam('url', [
            'clientId' => 'id'
        ]));

        $this->addActionButton($redirectButton);
        $this->addActionButton((new VisibilityWrapper(new EditButton()))->disableWhen('id', "12")->hideWhen('id', "14"));
        $this->addActionButton(new DeleteButton());
        
        // Add mass action buttons
        $this->addMassActionButton((new DeleteButton())->displayWithTitle("Delete Selected"));

        // Enable drag and drop for row reordering
        $this->setDraggableRows();
    }

    public function loadData(): void
    {
        $clients = Client::select('tblclients.id', 'tblclients.firstname', 'lastname', 'email', 'status');

        $dataProvider = new QueryDataProvider($clients);
        $dataProvider->setColumns([
            new \ModulesGarden\YourModule\Core\DataProviders\Column('tblclients.id'),
            new \ModulesGarden\YourModule\Core\DataProviders\Column('tblclients.firstname'),
        ]);
        $dataProvider->setDefaultSorting('tblclients.id', 'DESC');
        $this->setDataProvider($dataProvider);
    }

    protected function parseDataSetRecords(): void
    {
        // Modify how status field is displayed
        $this->dataSet->setFieldModifier('status', function($fieldName, $row, $fieldValue) {
            return (new LabelSuccess())
                ->setText($this->translate($fieldValue))
                ->displayAsStatusLabel();
        });

        $this->dataSet->modifyRecords();
    }
}
```

**DataTable with Array Data Provider:**
```php
<?php

namespace ModulesGarden\YourModule\App\UI\Admin\DataTable;

use ModulesGarden\YourModule\Components\DataTable\Column;
use ModulesGarden\YourModule\Components\DataTable\DataTable;
use ModulesGarden\YourModule\Components\CopyPasswordInline\CopyPasswordInline;
use ModulesGarden\YourModule\Components\Label\LabelSuccess;
use ModulesGarden\YourModule\Components\ListSimple\ListSimple;
use ModulesGarden\YourModule\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\YourModule\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\YourModule\Core\DataProviders\ArrayDataProvider;
use WHMCS\User\Client;

class ClientsArrayDataTable extends DataTable implements AdminAreaInterface, AjaxComponentInterface
{
    public function loadHtml(): void
    {
        $this->setTitle('Client Array Data Provider');

        $this->addColumn((new Column('id'))
                ->setSortable()
                ->setSearchable(true, Column::TYPE_INT))
            ->addColumn((new Column('firstname'))
                ->setSortable(true)
                ->setSearchable(true))
            ->addColumn((new Column('lastname'))
                ->setSortable(true)
                ->setSearchable(true))
            ->addColumn((new Column('company')));
    }

    public function loadData(): void
    {
        $clients = Client::select('id', 'firstname', 'lastname', 'companyname')->get()->toArray();

        $dataProvider = new ArrayDataProvider($clients);
        $dataProvider->setDefaultSorting('id', 'DESC');
        $this->setDataProvider($dataProvider);
    }

    protected function parseDataSetRecords(): void
    {
        // Make lastname copyable
        $this->dataSet->setFieldModifier('lastname', function($fieldName, $row, $fieldValue) {
            return (new CopyPasswordInline())->setText($fieldValue);
        });

        // Display firstname with company as labels
        $this->dataSet->setFieldModifier('firstname', function($fieldName, $row, $fieldValue) {
            $container = new ListSimple();
            $container->addItem((new LabelSuccess())->setText($fieldValue)->displayAsStatusLabel());
            $container->addItem((new LabelSuccess())->setText($row['companyname'])->displayAsStatusLabel());
            return $container;
        });

        $this->dataSet->modifyRecords();
    }
}
```

### Form Components

Forms handle user input and data submission with validation and CRUD operations.

**Form Container with Multiple Form Types:**
```php
<?php

namespace ModulesGarden\YourModule\App\UI\Admin\Forms;

use ModulesGarden\YourModule\Components\Button\ButtonSubmitSuccess;
use ModulesGarden\YourModule\Components\Container\Container;
use ModulesGarden\YourModule\Components\Widget\Widget;
use ModulesGarden\YourModule\Core\Components\Actions\FormSubmit;
use ModulesGarden\YourModule\Core\Contracts\Components\AdminAreaInterface;

class FormsContainer extends Container implements AdminAreaInterface
{
    public function loadHtml(): void
    {
        $this->oneColumnForm();
        $this->twoColumnForm();
        $this->formWithTabs();
        $this->autoSubmitForm();
    }

    private function oneColumnForm(): void
    {
        $form = new OneColumnForm();
        $widget = new Widget();
        $widget->setTitle('Form One Column');
        $widget->addElement($form);
        $widget->addElement((new ButtonSubmitSuccess())
            ->setTitle('Submit')
            ->onClick(new FormSubmit($form)));

        $this->addElement($widget);
    }

    private function twoColumnForm(): void
    {
        $form = new TwoColumnForm();
        $widget = new Widget();
        $widget->setTitle('Form Two Columns');
        $widget->addElement($form);
        $widget->addElement((new ButtonSubmitSuccess())
            ->setTitle('Submit')
            ->onClick(new FormSubmit($form)));

        $this->addElement($widget);
    }

    private function formWithTabs(): void
    {
        $form = new FormWithTabs();
        $widget = new Widget();
        $widget->setTitle('Form With Tabs');
        $widget->addElement($form);
        $widget->addElement((new ButtonSubmitSuccess())
            ->setTitle('Submit')
            ->onClick(new FormSubmit($form)));

        $this->addElement($widget);
    }

    private function autoSubmitForm(): void
    {
        $form = new AutoSubmitForm();
        $widget = new Widget();
        $widget->setTitle('Auto Submit Form');
        $widget->addElement($form);
        $widget->addElement((new ButtonSubmitSuccess())
            ->setTitle('Submit')
            ->onClick(new FormSubmit($form)));

        $this->addElement($widget);
    }
}
```

**One Column Form with Builder:**
```php
<?php

namespace ModulesGarden\YourModule\App\UI\Admin\Forms;

use ModulesGarden\YourModule\Components\Form\Builder\BuilderCreator;
use ModulesGarden\YourModule\Components\Form\Form;
use ModulesGarden\YourModule\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\YourModule\Core\Contracts\Components\AjaxComponentInterface;

class OneColumnForm extends Form implements AdminAreaInterface, AjaxComponentInterface
{
    public function __construct()
    {
        parent::__construct();
        $this->builder = BuilderCreator::oneColumnHalfPage($this);
    }

    public function loadHtml(): void
    {
        // Form fields will be added using the builder
        $this->addFormFields();
    }

    private function addFormFields(): void
    {
        $this->builder->createField(FormInputText::class, 'name')
            ->setLabel($this->translate('Name'))
            ->setRequired(true);

        $this->builder->createField(FormInputEmail::class, 'email')
            ->setLabel($this->translate('Email'))
            ->setRequired(true);

        $this->builder->createField(FormInputPassword::class, 'password')
            ->setLabel($this->translate('Password'))
            ->setRequired(true);

        $this->builder->createField(Switcher::class, 'active')
            ->setLabel($this->translate('Active'))
            ->setOnOffMode();
    }
}
```

### DataTable Components

DataTables are powerful components for displaying tabular data with search, sort, and pagination.

**DataTable Pattern:**
```php
<?php

namespace ModulesGarden\YourModule\App\UI\Admin\YourSection\Pages;

use ModulesGarden\YourModule\Components\DataTable\Column;
use ModulesGarden\YourModule\Components\DataTable\DataTable;
use ModulesGarden\YourModule\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\YourModule\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\YourModule\Core\DataProviders\QueryDataProvider;

class YourDataTable extends DataTable implements AdminAreaInterface, AjaxComponentInterface
{
    public function loadHtml(): void
    {
        $this->setTitle($this->translate('table_title'));

        // Define columns
        $this->addColumn(
            (new Column('name'))->setTitle($this->translate('name'))
                ->setSearchable(true, Column::TYPE_STRING)
                ->setSortable()
        )
        ->addColumn(
            (new Column('status'))->setTitle($this->translate('status'))
                ->setSearchable(true, Column::TYPE_SELECT)
                ->setSortable()
        )
        ->addColumn(
            (new Column('created_at'))->setTitle($this->translate('created_at'))
                ->setSortable()
        );

        // Add action buttons
        $this->addActionButton(new EditButton())
            ->addActionButton(new DeleteButton());
    }

    public function loadData(): void
    {
        $query = YourModel::select('id', 'name', 'status', 'created_at');
        
        $dataProvider = new QueryDataProvider();
        $dataProvider->setDefaultSorting('name', 'ASC');
        $dataProvider->setQuery($query);
        
        $this->setDataProvider($dataProvider);
    }

    public function parseDataSetRecords(): void
    {
        // Modify field display
        $this->dataSet->setFieldModifier('status', function ($fieldName, $row, $fieldValue) {
            return (new Label())
                ->displayAsStatusLabel()
                ->setText($this->translate($fieldValue))
                ->setType($fieldValue === 'active' ? Type::SUCCESS : Type::DEFAULT)
                ->setSize(Size::MEDIUM);
        });

        $this->dataSet->modifyRecords();
    }
}
```

### Form Components

Forms handle user input and data submission with validation and CRUD operations.

**Form Pattern:**
```php
<?php

namespace ModulesGarden\YourModule\App\UI\Admin\YourSection\Pages;

use ModulesGarden\YourModule\Components\Form\AbstractForm;
use ModulesGarden\YourModule\Core\Contracts\Components\AdminAreaInterface;

class YourForm extends AbstractForm implements AdminAreaInterface
{
    protected $providerDefaultAction = CrudProvider::ACTION_READ;
    protected $providerActionsToValidate = [
        CrudProvider::ACTION_CREATE,
        CrudProvider::ACTION_UPDATE,
    ];

    public function loadHtml(): void
    {
        $this->setTitle($this->translate('form_title'));

        // Add form fields
        $this->addField(
            (new FormInputText('name'))
                ->setLabel($this->translate('name'))
                ->setRequired(true)
        );

        $this->addField(
            (new FormSelect('status'))
                ->setLabel($this->translate('status'))
                ->setOptions([
                    'active' => $this->translate('active'),
                    'inactive' => $this->translate('inactive')
                ])
        );

        // Add submit button
        $this->addButton(
            (new Button())
                ->setText($this->translate('save'))
                ->setType(Type::PRIMARY)
                ->onClick(new FormSubmit($this))
        );
    }

    public function loadData(): void
    {
        // Load data for editing
        if ($id = Request::get('id')) {
            $model = YourModel::find($id);
            $this->setData($model->toArray());
        }
    }
}
```

### Button Components

Buttons handle user interactions and trigger actions.

**Button Pattern:**
```php
<?php

namespace ModulesGarden\YourModule\App\UI\Admin\YourSection\Buttons;

use ModulesGarden\YourModule\Components\IconButton\IconButton;
use ModulesGarden\YourModule\Core\Components\Actions\Redirect;
use ModulesGarden\YourModule\Core\Contracts\Components\AdminAreaInterface;

class YourButton extends IconButton implements AdminAreaInterface
{
    public function loadHtml(): void
    {
        $this->setTitle($this->translate('title'))
            ->setIcon('icon-name')
            ->onClick(new Redirect('target-url', ['param' => ':id']));
    }
}
```

### Modal Components

**Creating Modal Components:**
```php
<?php

namespace ModulesGarden\YourModule\App\UI\Modals;

use ModulesGarden\YourModule\Components\Modal\ModalDanger;
use ModulesGarden\YourModule\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\YourModule\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\YourModule\Core\Support\Facades\Request;

class DeleteModal extends ModalDanger implements AdminAreaInterface, AjaxComponentInterface
{
    public function loadHtml(): void
    {
        $this->setTitle($this->translate('title'));
        $this->setContent($this->translate('content', ['item:' => Request::get('formData')['name']]));
        $this->addElement(new DeleteForm());
    }
}
```

**Modal Types Available:**
- `Modal` - Standard modal
- `ModalDanger` - Warning/danger modals
- `ModalSuccess` - Success confirmation modals

### Widget Components for Settings

**Creating Settings Widgets with Form Builders:**
```php
<?php

namespace ModulesGarden\YourModule\App\UI\Settings\Sections;

use ModulesGarden\YourModule\Components\Button\ButtonSubmitSuccess;
use ModulesGarden\YourModule\Components\Form\Builder\BuilderCreator;
use ModulesGarden\YourModule\Components\Form\Form;
use ModulesGarden\YourModule\Components\Number\Number;
use ModulesGarden\YourModule\Components\Switcher\Switcher;
use ModulesGarden\YourModule\Components\FormInputText\FormInputText;
use ModulesGarden\YourModule\Core\Components\Actions\FormSubmit;
use ModulesGarden\YourModule\App\Helpers\Validators\NumberValidator;

class SettingsContainer extends Form implements AdminAreaInterface, AjaxComponentInterface
{
    protected string $provider = SettingProvider::class;
    protected string $providerAction = SettingProvider::ACTION_UPDATE;

    public function loadHtml(): void
    {
        $this->general();
        $this->loadBalancer();
        $this->globalLimits();
        
        $buttonSubmit = (new ButtonSubmitSuccess())
            ->onClick(new FormSubmit($this));
        $this->addElement($buttonSubmit);
    }

    private function general(): void
    {
        $general = new GeneralWidget();
        $builderGeneral = BuilderCreator::oneColumnInContainer($this, $general);

        $field = new Number();
        $field->setName('minimumVMID')
            ->addValidator(new NumberValidator(1, 100000000))
            ->setDefaultValue(100);
        $builderGeneral->addField($field);

        $field = new Switcher();
        $field->setName('debug')
            ->setOnOffMode();
        $builderGeneral->addField($field, true);

        $this->addElement($general);
    }

    private function loadBalancer(): void
    {
        $loadBalancer = new LoadBalancerWidget();
        $builderLoadBalancer = BuilderCreator::oneColumnInContainer($this, $loadBalancer);

        $field = new Number();
        $field->setName('vmsWeight')
            ->addValidator(new NumberValidator(1, null, false, true))
            ->setDefaultValue(1000);
        $builderLoadBalancer->addField($field, true);

        $this->addElement($loadBalancer);
    }
}
```

### Progress Bar Component Examples

**Progress Bars Widget with Different Sizes:**
```php
<?php

namespace ModulesGarden\YourModule\App\UI\Widgets;

use ModulesGarden\YourModule\Components\ProgressBar\ProgressBar;
use ModulesGarden\YourModule\Components\Widget\Widget;
use ModulesGarden\YourModule\Core\Components\Enums\Size;
use ModulesGarden\YourModule\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\YourModule\Core\Contracts\Components\AjaxComponentInterface;

class ResourceUsageWidget extends Widget implements AjaxComponentInterface, AdminAreaInterface
{
    public function loadHtml(): void
    {
        $this->setTitle("Resource Usage");

        // Small progress bars for compact display
        $memoryBar = (new ProgressBar())
            ->setText("Memory (1024/3000 MB)")
            ->setFill(34)
            ->setSize(Size::SMALL);

        $cpuBar = (new ProgressBar())
            ->setText("vCPU (2/12 Cores)")
            ->setFill(17)
            ->setSize(Size::SMALL);

        $diskBar = (new ProgressBar())
            ->setText("Disks (8/100 GiB)")
            ->setFill(8)
            ->setSize(Size::SMALL);

        $networkBar = (new ProgressBar())
            ->setText("Networks (3/4)")
            ->setFill(75)
            ->setSize(Size::SMALL);

        $this->addElement($memoryBar);
        $this->addElement($cpuBar);
        $this->addElement($diskBar);
        $this->addElement($networkBar);
    }
}
```

### Usage Widget Component Examples

**Usage Widgets with Grid Layout:**
```php
<?php

namespace ModulesGarden\YourModule\App\UI\Widgets;

use ModulesGarden\YourModule\Components\Container\Container;
use ModulesGarden\YourModule\Components\Grid\Grid;
use ModulesGarden\YourModule\Components\UsageWidget\UsageWidget;
use ModulesGarden\YourModule\Components\Widget\Widget;
use ModulesGarden\YourModule\Core\Contracts\Components\AdminAreaInterface;

class SystemUsageWidgets extends Container implements AdminAreaInterface
{
    public function loadHtml(): void
    {
        $widget = new Widget();
        $widget->setTitle("System Usage");

        // Create individual usage widgets
        $usageWidgetCpu = (new UsageWidget())
            ->setTitle("CPU")
            ->setIcon("memory")
            ->setUsage(rand(0, 10))
            ->setLimit(10)
            ->setUnit("cores");

        $usageWidgetRam = (new UsageWidget())
            ->setTitle("RAM")
            ->setIcon("chip")
            ->setUsage(rand(0, 32))
            ->setLimit(32)
            ->setUnit("GB");

        $usageWidgetStorage = (new UsageWidget())
            ->setTitle("Storage")
            ->setIcon("database")
            ->setUsage(rand(0, 1000))
            ->setLimit(1000)
            ->setUnit("GB");

        $usageWidgetNetwork = (new UsageWidget())
            ->setTitle("Network")
            ->setIcon("network")
            ->setUsage(rand(0, 10))
            ->setLimit(10)
            ->setUnit("Gbps");

        // Arrange in 2x2 grid layout
        $grid = new Grid();
        $grid->setRows([
            [
                [$usageWidgetCpu, 6], [$usageWidgetRam, 6]
            ],
            [
                [$usageWidgetStorage, 6], [$usageWidgetNetwork, 6]
            ]
        ]);

        $widget->addElement($grid);
        $this->addElement($widget);
    }
}
```

### Tile Button Component Examples

**Tiles Bar with Action Buttons:**
```php
<?php

namespace ModulesGarden\YourModule\App\UI\Widgets;

use ModulesGarden\YourModule\Components\Container\Container;
use ModulesGarden\YourModule\Components\TileButton\TileButton;
use ModulesGarden\YourModule\Components\TilesBar\TilesBar;
use ModulesGarden\YourModule\Core\Components\Actions\ModalLoad;
use ModulesGarden\YourModule\Core\ModuleConstants;

class ActionTilesWidget extends Container
{
    public function loadHtml(): void
    {
        $tiles = new TilesBar();
        $tiles->setTitle('Server Actions');

        // Create action tiles with images and click handlers
        $tiles->addTile(
            (new TileButton())
                ->setImagePath(YourModule::getFullPath('resources', 'assets', 'img', 'actions', 'backup-jobs.png'))
                ->setTitle('Backup Jobs')
                ->onClick(new ModalLoad(new BackupJobsModal()))
        );

        $tiles->addTile(
            (new TileButton())
                ->setImagePath(YourModule::getFullPath('resources', 'assets', 'img', 'actions', 'backups.png'))
                ->setTitle('Backups')
                ->onClick(new ModalLoad(new BackupsModal()))
        );

        $tiles->addTile(
            (new TileButton())
                ->setImagePath(YourModule::getFullPath('resources', 'assets', 'img', 'actions', 'console.png'))
                ->setTitle('Console')
                ->onClick(new ModalLoad(new ConsoleModal()))
        );

        $this->addElement($tiles);
    }
}
```

### Tree View Component Examples

**Tree List Container with Dynamic Items:**
```php
<?php

namespace ModulesGarden\YourModule\App\UI\Widgets;

use ModulesGarden\YourModule\Components\TreeListContainer\TreeListContainer;
use ModulesGarden\YourModule\Components\TreeListItem\TreeListItem;
use ModulesGarden\YourModule\Components\TreeListSubItem\TreeListSubItem;
use ModulesGarden\YourModule\Components\Widget\Widget;

class FileTreeWidget extends Widget
{
    public function loadHtml(): void
    {
        $this->setTitle('File Tree');

        $treeViewContainer = new TreeListContainer();

        // Create tree structure with main items and sub-items
        for ($i = 0; $i < 5; $i++) {
            $item = new TreeListItem();
            $item->setTitle("Folder {$i}")
                ->setIcon('folder')
                ->setExpandable(true);

            // Add sub-items to each folder
            for ($j = 0; $j < 3; $j++) {
                $subItem = new TreeListSubItem();
                $subItem->setTitle("File {$i}-{$j}.txt")
                    ->setIcon('file-text')
                    ->onClick(new OpenFileModal());
                
                $item->addSubItem($subItem);
            }

            $treeViewContainer->addElement($item);
        }

        $this->addElement($treeViewContainer);
    }
}
```

### Table Simple Component Examples

**Simple Table with Custom Columns and Records:**
```php
<?php

namespace ModulesGarden\YourModule\App\UI\Tables;

use ModulesGarden\YourModule\Components\TableSimple\TableSimple;
use ModulesGarden\YourModule\Components\TableSimple\Column\Column;
use ModulesGarden\YourModule\Components\TableSimple\Record\Record;
use ModulesGarden\YourModule\Components\Widget\Widget;

class ServiceInfoTable extends Widget
{
    public function loadHtml(): void
    {
        $this->setTitle('Service Information');

        $table = new TableSimple();
        
        // Add table columns
        $table->addColumn(new Column('Property'));
        $table->addColumn(new Column('Value'));
        $table->addColumn(new Column('Status'));

        // Add table records
        $records = [
            ['Hostname', 'server.example.com', 'Active'],
            ['IP Address', '192.168.1.100', 'Active'],
            ['Operating System', 'Ubuntu 22.04 LTS', 'Active'],
            ['CPU Cores', '4 vCores', 'Active'],
            ['Memory', '8 GB', 'Active'],
            ['Storage', '100 GB SSD', 'Active']
        ];

        foreach ($records as $recordData) {
            $record = new Record();
            $record->setValues($recordData);
            $table->addRecord($record);
        }

        $this->addElement($table);
    }
}
```

### Component Builder Helper Methods

**Builder Creation Methods for Complex Layouts:**
```php
// Single column builder
$builder = BuilderCreator::oneColumn($this);

// Two column builder for side-by-side fields
$builder = BuilderCreator::twoColumns($this);

// Single column in container widget
$widget = new CustomWidget();
$builder = BuilderCreator::oneColumnInContainer($this, $widget);

// Two columns in container widget for responsive forms
$widget = new CustomWidget();
$builder = BuilderCreator::twoColumnsInContainer($this, $widget);

// Half page single column for compact forms
$builder = BuilderCreator::oneColumnHalfPage($this);

// Custom builder configuration with specific layout
$container = new Container();
$rowFluid = new RowFluid();
$container->addElement($rowFluid);

$builder = (new Builder($this))
    ->setDefaultFormGroup(new FormGroupHalfWidth())
    ->setDefaultContainer($rowFluid);
```

**Builder Field Addition Patterns:**
```php
// Add field instance directly
$field = new FormInputText();
$field->setName('name')->required();
$builder->addField($field);

// Add optional field (second parameter true)
$builder->addField($field, true); // true = optional

// Create and add field by class name
$builder->createField(FormInputText::class, 'name');

// Create optional field
$builder->createField(Switcher::class, 'enabled', true);

// Chain field methods after creation
$builder->createField(Number::class, 'port')
    ->setRange(1, 65535)
    ->required();

// Add field with custom validation
$builder->createField(FormInputText::class, 'email')
    ->addValidator(new EmailValidator())
    ->required();
```

### BlockZeroData Component

The BlockZeroData component is designed for displaying empty states, error messages, and "no data found" scenarios. It provides a clean, centered layout with title, description, optional icon, and action buttons.

**Basic BlockZeroData Usage:**
```php
<?php

namespace ModulesGarden\YourModule\App\UI\Admin\EmptyStates;

use ModulesGarden\YourModule\Components\BlockZeroData\BlockZeroData;
use ModulesGarden\YourModule\Components\Button\ButtonPrimary;
use ModulesGarden\YourModule\Core\Components\Actions\Redirect;
use ModulesGarden\YourModule\Core\Contracts\Components\AdminAreaInterface;

class NoDataFound extends BlockZeroData implements AdminAreaInterface
{
    public function loadHtml(): void
    {
        $this->setTitle('No Data Found');
        $this->setDescription('There are currently no items to display. Click the button below to create your first item.');
        $this->setIcon('database');

        // Add action button
        $createButton = new ButtonPrimary();
        $createButton->setTitle('Create New Item')
            ->setIcon('plus')
            ->onClick(new Redirect('items', 'create'));
        
        $this->addElement($createButton);
    }
}
```

**Custom Error Page with BlockZeroData:**
```php
<?php

namespace ModulesGarden\YourModule\App\UI\Admin\ErrorPages;

use ModulesGarden\YourModule\Core\Contracts\Actions\AdminAreaInterface;

class CustomAction extends AbstractAction implements AdminAreaInterface
{
    protected string $jsActionName = 'customAction';

    public function run(): array
    {
        // Action logic here
        return [
            'success' => true,
            'message' => 'Action completed successfully'
        ];
    }
}
```

### Common Actions

#### FormSubmit
Handles form submission and validation.

```php
<?php

use ModulesGarden\YourModule\Core\Components\Actions\FormSubmit;

$submitAction = new FormSubmit($formComponent);
$submitAction->setSuccessMessage('Data saved successfully');
```

#### Redirect
Redirects to another page or URL.

```php
<?php

use ModulesGarden\YourModule\Core\Components\Actions\Redirect;

// Redirect to internal page
$redirect = new Redirect('controller', 'method', ['param' => 'value']);

// Redirect to external URL
$externalRedirect = new Redirect('https://example.com');
```

#### ModalLoad
Loads content in a modal dialog.

```php
<?php

use ModulesGarden\YourModule\Core\Components\Actions\ModalLoad;

$modalAction = new ModalLoad(CustomModal::class);
$modalAction->setSize('large');
```

## Best Practices

### Component Organization
- Place components in logical directory structures
- Use descriptive namespaces that reflect the component hierarchy
- Group related components together
- Separate admin and client components

### Performance Optimization
- Use AJAX loading for heavy operations
- Implement proper caching strategies
- Minimize database queries in loadHtml methods
- Use data providers for efficient data loading

### Code Quality
- Follow SOLID principles
- Use dependency injection
- Implement proper error handling
- Write comprehensive PHPDoc comments
- Use type hints for all method parameters and return types

### Security
- Validate all user inputs
- Use proper authorization checks
- Sanitize data before display
- Implement CSRF protection for forms
- Use parameterized queries for database operations

### Testing
- Write unit tests for models and services
- Test UI components in isolation
- Implement integration tests for complete workflows
- Use mock objects for external dependencies

### Documentation
- Document all public methods and properties
- Provide usage examples for complex components
- Keep translation keys organized and descriptive
- Maintain up-to-date API documentation
