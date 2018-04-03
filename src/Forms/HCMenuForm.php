<?php
/**
 * @copyright 2018 interactivesolutions
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
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

use HoneyComb\Menu\Enum\HCMenuTargetEnum;
use HoneyComb\Menu\Enum\HCMenuUrlTargetEnum;
use HoneyComb\Menu\Repositories\HCMenuTypeRepository;
use HoneyComb\Starter\Forms\HCBaseForm;

/**
 * Class HCMenuForm
 * @package HoneyComb\Menu\Forms
 */
class HCMenuForm extends HCBaseForm
{
    /**
     * @var HCMenuTypeRepository
     */
    private $menuTypeRepository;

    public function __construct(HCMenuTypeRepository $menuTypeRepository)
    {
        $this->menuTypeRepository = $menuTypeRepository;
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
            'storageUrl' => route('admin.api.menu'),
            'buttons' => [
                'submit' => [
                    'label' => $this->getSubmitLabel($edit),
                ],
            ],
            'structure' => $this->getStructure($edit),
        ];

        if ($this->multiLanguage) {
            $form['availableLanguages'] = [];
        }

        //TOTO implement honeycomb-languages package getAvailableLanguages

        return $form;
    }

    /**
     * @param string $prefix
     * @return array
     */
    public function getStructureNew(string $prefix): array
    {
        return [
            $prefix . 'language_code' =>
                [
                    "type" => "dropDownList",
                    "label" => trans("HCMenu::menu.language_code"),
                    "required" => 1,
                    "options" => getHCLanguagesOptions('content'),
                ],
            $prefix . 'type_id' =>
                [
                    "type" => "dropDownList",
                    "label" => trans("HCMenu::menu.type_id"),
                    "required" => 1,
                    'options' => optimizeTranslationOptions($this->menuTypeRepository->all()),
                ],
            $prefix . 'target' =>
                [
                    "type" => "dropDownList",
                    "label" => trans("HCMenu::menu.target"),
                    "required" => 1,
                    "options" => HCMenuTargetEnum::options(),
                ],
            $prefix . 'label' =>
                [
                    "type" => "singleLine",
                    "label" => trans("HCMenu::menu.label"),
                    "required" => 1,
                ],
            $prefix . 'icon' =>
                [
                    "type" => "singleLine",
                    "label" => trans("HCMenu::menu.icon"),

                ],
            $prefix . 'url' =>
                [
                    "type" => "textArea",
                    "label" => trans("HCMenu::menu.url"),
                    "dependencies" => [
                        $prefix . 'target' => [
                            'values' => [HCMenuTargetEnum::url()->id()],
                        ],
                    ],

                ],
            $prefix . 'url_text' =>
                [
                    "type" => "singleLine",
                    "label" => trans("HCMenu::menu.url_text"),
                    "dependencies" => [
                        $prefix . 'target' => [
                            'values' => [HCMenuTargetEnum::url()->id()],
                        ],
                    ],

                ],
            $prefix . 'url_target' =>
                [
                    "type" => "dropDownList",
                    "label" => trans("HCMenu::menu.url_target"),
                    "options" => HCMenuUrlTargetEnum::options(),
                    "dependencies" => [
                        $prefix . 'target' => [
                            'values' => [HCMenuTargetEnum::url()->id()],
                        ],
                    ],
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
