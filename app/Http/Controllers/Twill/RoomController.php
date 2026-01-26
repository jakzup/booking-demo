<?php

namespace App\Http\Controllers\Twill;

use A17\Twill\Http\Controllers\Admin\ModuleController as BaseModuleController;
use A17\Twill\Models\Contracts\TwillModelContract;
use A17\Twill\Services\Forms\Fields\Input;
use A17\Twill\Services\Forms\Fields\Wysiwyg;
use A17\Twill\Services\Forms\Fields\Checkbox;
use A17\Twill\Services\Forms\Fields\Medias;
use A17\Twill\Services\Forms\Form;
use A17\Twill\Services\Listings\Columns\Text;
use A17\Twill\Services\Listings\TableColumns;

class RoomController extends BaseModuleController
{
    protected $moduleName = 'rooms';

    protected function setUpController(): void
    {
        $this->setModuleName('rooms');
        $this->setPermalinkBase('rooms');
    }

    public function getForm(TwillModelContract $model): Form
    {
        $form = parent::getForm($model);

        $form->add(
            Input::make()->name('title')->label('Room Name')->translatable()->required()
        );

        $form->add(
            Input::make()->name('price_per_night')->label('Price per Night')->type('number')->step('0.01')->required()
        );

        $form->add(
            Wysiwyg::make()->name('description')->label('Description')->translatable()
        );

        $form->add(
            Medias::make()->name('cover')->label('Cover Image')->max(1)
        );

        $form->add(
            Medias::make()->name('gallery')->label('Gallery Images')->max(10)
        );

        return $form;
    }

    protected function additionalIndexTableColumns(): TableColumns
    {
        $table = parent::additionalIndexTableColumns();

        $table->add(
            Text::make()->field('price_per_night')->title('Price/Night')
        );

        return $table;
    }
}
