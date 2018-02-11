<?php
/**
 * @copyright 2017 interactivesolutions
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the 'Software'), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED 'AS IS', WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 *
 * Contact InteractiveSolutions:
 * E-mail: hello@interactivesolutions.lt
 * http://www.interactivesolutions.lt
 */

declare(strict_types = 1);

namespace HoneyComb\Menu\Forms;

use HoneyComb\Menu\Repositories\HCMenuTypeRepository;
use HoneyComb\Starter\Forms\HCBaseForm;

/**
 * Class HCMenuGroupForm
 * @package HoneyComb\Menu\Forms
 */
class HCMenuGroupForm extends HCBaseForm
{
    /**
     * @var bool
     */
    protected $multiLanguage = true;
    /**
     * @var HCMenuTypeRepository
     */
    private $typeRepository;

    public function __construct(HCMenuTypeRepository $typeRepository)
    {
        $this->typeRepository = $typeRepository;
    }

    /**
     * Creating form
     *
     * @param bool $edit
     * @return array
     */
    public function createForm(bool $edit = false): array
    {
        $form = [
            'storageUrl' => route('admin.api.menu.group'),
            'buttons' => [
                'submit' => [
                    'label' => $this->getSubmitLabel($edit),
                ],
            ],
            'structure' => $this->getStructure($edit),
        ];

        if ($this->multiLanguage) {
            $form['availableLanguages'] = getHCContentLanguages();
        }

        return $form;
    }

    /**
     * @param string $prefix
     * @return array
     */
    public function getStructureNew(string $prefix): array
    {
        return [
            $prefix . 'type_id' =>
                [
                    'type' => 'dropDownList',
                    'label' => trans('HCMenu::menu_group.type'),
                    'required' => 1,
                    'requiredVisible' => 1,
                    'options' => $this->typeRepository->all()->map(function($item) {

                        $formatted = ['id' => $item->id];

                        if ($item->translation)
                            $formatted['label'] = $item->translation->label;

                        return $formatted;
                    }),
                ],
            $prefix . 'translations.label' =>
                [
                    'type' => 'singleLine',
                    'label' => trans('HCMenu::menu_group.label'),
                    'multiLanguage' => 1,
                    'required' => 1,
                    'requiredVisible' => 1,
                ],
            $prefix . 'translations.description' =>
                [
                    'type' => 'textArea',
                    'label' => trans('HCMenu::menu_group.description'),
                    'multiLanguage' => 1,
                ],
        ];
    }

    /**
     * @param string $prefix
     * @return array
     */
    public function getStructureEdit(string $prefix): array
    {
        return $this->getStructureNew($prefix);
    }
}
