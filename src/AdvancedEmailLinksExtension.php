<?php

namespace gorriecoe\AdvancedEmailLinks;

use gorriecoe\Link\Models\Link;
use SilverStripe\Forms\EmailField;
use SilverStripe\Forms\TextareaField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;
use SilverStripe\ORM\DataExtension;
use UncleCheese\DisplayLogic\Forms\Wrapper;

/**
 * Adds cc, bcc, subject and body options to email link type for Link Object
 *
 * @package silverstripe-advancedemaillinks
 */
class AdvancedEmailLinksExtension extends DataExtension
{
    /**
     * Database fields
     * @var array
     */
    private static $db = [
        'EmailCC' => 'Varchar',
        'EmailBCC' => 'Varchar',
        'EmailSubject' => 'Varchar',
        'EmailBody' => 'Varchar',
    ];

    /**
     * Update Fields
     * @return FieldList
     */
    public function updateCMSFields(FieldList $fields)
    {
        $owner = $this->owner;
        $fields->removeByName([
            'Email'
        ]);
        $fields->insertAfter(
            'Type',
            Wrapper::create(
                EmailField::create(
                    'Email',
                    _t(Link::class . '.EMAILADDRESS', 'Email')
                ),
                EmailField::create(
                    'EmailCC',
                    _t(__CLASS__ . '.EMAILCC', 'CC')
                )
                ->setAttribute(
                    'placeholder',
                    _t(__CLASS__ . '.OPTIONAL', 'Optional')
                ),
                EmailField::create(
                    'EmailBCC',
                    _t(__CLASS__ . '.EMAILBCC', 'BCC')
                )
                ->setAttribute(
                    'placeholder',
                    _t(__CLASS__ . '.OPTIONAL', 'Optional')
                ),
                TextField::create(
                    'EmailSubject',
                    _t(__CLASS__ . '.EMAILSUBJECT', 'Subject')
                )
                ->setAttribute(
                    'placeholder',
                    _t(__CLASS__ . '.OPTIONAL', 'Optional')
                ),
                TextareaField::create(
                    'EmailBody',
                    _t(__CLASS__ . '.EMAILBODY', 'Body')
                )
                ->setAttribute(
                    'placeholder',
                    _t(__CLASS__ . '.OPTIONAL', 'Optional')
                )
            )
            ->displayIf('Type')->isEqualTo('Email')->end()
        );
        return $fields;
    }

    /**
     * Update LinkURL
     */
    public function updateLinkURL(&$linkURL)
    {
        $owner = $this->owner;
        if ($owner->Type == 'Email') {
            $options = [];
            if ($owner->EmailCC) {
                $options['cc'] = 'cc=' . $owner->EmailCC;
            }
            if ($owner->EmailBCC) {
                $options['bcc'] = 'bcc=' . $owner->EmailBCC;
            }
            if ($owner->EmailSubject) {
                $options['subject'] = 'subject=' . rawurlencode($owner->EmailSubject);
            }
            if ($owner->EmailBody) {
                $options['body'] = 'body=' . rawurlencode($owner->EmailBody);
            }
            $options = implode('&', $options);
            $linkURL = implode('?', [
                $linkURL,
                $options
            ]);
        }
    }
}
