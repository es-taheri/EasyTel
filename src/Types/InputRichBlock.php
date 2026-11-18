<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object represents a block in a rich formatted message to be sent. Currently, it can be any of the following types:
 * @method self inputrichblockparagraph(InputRichBlockParagraph $value)
 * @method self inputrichblocksectionheading(InputRichBlockSectionHeading $value)
 * @method self inputrichblockpreformatted(InputRichBlockPreformatted $value)
 * @method self inputrichblockfooter(InputRichBlockFooter $value)
 * @method self inputrichblockdivider(InputRichBlockDivider $value)
 * @method self inputrichblockmathematicalexpression(InputRichBlockMathematicalExpression $value)
 * @method self inputrichblockanchor(InputRichBlockAnchor $value)
 * @method self inputrichblocklist(InputRichBlockList $value)
 * @method self inputrichblockblockquotation(InputRichBlockBlockQuotation $value)
 * @method self inputrichblockexpandableblockquotation(InputRichBlockExpandableBlockQuotation $value)
 * @method self inputrichblockpullquotation(InputRichBlockPullQuotation $value)
 * @method self inputrichblockcollage(InputRichBlockCollage $value)
 * @method self inputrichblockslideshow(InputRichBlockSlideshow $value)
 * @method self inputrichblocktable(InputRichBlockTable $value)
 * @method self inputrichblockdetails(InputRichBlockDetails $value)
 * @method self inputrichblockmap(InputRichBlockMap $value)
 * @method self inputrichblockbuttons(InputRichBlockButtons $value)
 * @method self inputrichblockanimation(InputRichBlockAnimation $value)
 * @method self inputrichblockaudio(InputRichBlockAudio $value)
 * @method self inputrichblockdocument(InputRichBlockDocument $value)
 * @method self inputrichblockphoto(InputRichBlockPhoto $value)
 * @method self inputrichblockvideo(InputRichBlockVideo $value)
 * @method self inputrichblockvoicenote(InputRichBlockVoiceNote $value)
 * @method self inputrichblockthinking(InputRichBlockThinking $value)
 */
class InputRichBlock
{
    protected const _UNION_BASE = true;
    /** @var InputRichBlockParagraph */
    public InputRichBlockParagraph $inputrichblockparagraph;
    /** @var InputRichBlockSectionHeading */
    public InputRichBlockSectionHeading $inputrichblocksectionheading;
    /** @var InputRichBlockPreformatted */
    public InputRichBlockPreformatted $inputrichblockpreformatted;
    /** @var InputRichBlockFooter */
    public InputRichBlockFooter $inputrichblockfooter;
    /** @var InputRichBlockDivider */
    public InputRichBlockDivider $inputrichblockdivider;
    /** @var InputRichBlockMathematicalExpression */
    public InputRichBlockMathematicalExpression $inputrichblockmathematicalexpression;
    /** @var InputRichBlockAnchor */
    public InputRichBlockAnchor $inputrichblockanchor;
    /** @var InputRichBlockList */
    public InputRichBlockList $inputrichblocklist;
    /** @var InputRichBlockBlockQuotation */
    public InputRichBlockBlockQuotation $inputrichblockblockquotation;
    /** @var InputRichBlockExpandableBlockQuotation */
    public InputRichBlockExpandableBlockQuotation $inputrichblockexpandableblockquotation;
    /** @var InputRichBlockPullQuotation */
    public InputRichBlockPullQuotation $inputrichblockpullquotation;
    /** @var InputRichBlockCollage */
    public InputRichBlockCollage $inputrichblockcollage;
    /** @var InputRichBlockSlideshow */
    public InputRichBlockSlideshow $inputrichblockslideshow;
    /** @var InputRichBlockTable */
    public InputRichBlockTable $inputrichblocktable;
    /** @var InputRichBlockDetails */
    public InputRichBlockDetails $inputrichblockdetails;
    /** @var InputRichBlockMap */
    public InputRichBlockMap $inputrichblockmap;
    /** @var InputRichBlockButtons */
    public InputRichBlockButtons $inputrichblockbuttons;
    /** @var InputRichBlockAnimation */
    public InputRichBlockAnimation $inputrichblockanimation;
    /** @var InputRichBlockAudio */
    public InputRichBlockAudio $inputrichblockaudio;
    /** @var InputRichBlockDocument */
    public InputRichBlockDocument $inputrichblockdocument;
    /** @var InputRichBlockPhoto */
    public InputRichBlockPhoto $inputrichblockphoto;
    /** @var InputRichBlockVideo */
    public InputRichBlockVideo $inputrichblockvideo;
    /** @var InputRichBlockVoiceNote */
    public InputRichBlockVoiceNote $inputrichblockvoicenote;
    /** @var InputRichBlockThinking */
    public InputRichBlockThinking $inputrichblockthinking;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * This object represents a block in a rich formatted message to be sent. Currently, it can be any of the following types:
     * @param InputRichBlockParagraph|null $inputrichblockparagraph
     * @param InputRichBlockSectionHeading|null $inputrichblocksectionheading
     * @param InputRichBlockPreformatted|null $inputrichblockpreformatted
     * @param InputRichBlockFooter|null $inputrichblockfooter
     * @param InputRichBlockDivider|null $inputrichblockdivider
     * @param InputRichBlockMathematicalExpression|null $inputrichblockmathematicalexpression
     * @param InputRichBlockAnchor|null $inputrichblockanchor
     * @param InputRichBlockList|null $inputrichblocklist
     * @param InputRichBlockBlockQuotation|null $inputrichblockblockquotation
     * @param InputRichBlockExpandableBlockQuotation|null $inputrichblockexpandableblockquotation
     * @param InputRichBlockPullQuotation|null $inputrichblockpullquotation
     * @param InputRichBlockCollage|null $inputrichblockcollage
     * @param InputRichBlockSlideshow|null $inputrichblockslideshow
     * @param InputRichBlockTable|null $inputrichblocktable
     * @param InputRichBlockDetails|null $inputrichblockdetails
     * @param InputRichBlockMap|null $inputrichblockmap
     * @param InputRichBlockButtons|null $inputrichblockbuttons
     * @param InputRichBlockAnimation|null $inputrichblockanimation
     * @param InputRichBlockAudio|null $inputrichblockaudio
     * @param InputRichBlockDocument|null $inputrichblockdocument
     * @param InputRichBlockPhoto|null $inputrichblockphoto
     * @param InputRichBlockVideo|null $inputrichblockvideo
     * @param InputRichBlockVoiceNote|null $inputrichblockvoicenote
     * @param InputRichBlockThinking|null $inputrichblockthinking
     */
    public static function make(InputRichBlockParagraph $inputrichblockparagraph = null, InputRichBlockSectionHeading $inputrichblocksectionheading = null, InputRichBlockPreformatted $inputrichblockpreformatted = null, InputRichBlockFooter $inputrichblockfooter = null, InputRichBlockDivider $inputrichblockdivider = null, InputRichBlockMathematicalExpression $inputrichblockmathematicalexpression = null, InputRichBlockAnchor $inputrichblockanchor = null, InputRichBlockList $inputrichblocklist = null, InputRichBlockBlockQuotation $inputrichblockblockquotation = null, InputRichBlockExpandableBlockQuotation $inputrichblockexpandableblockquotation = null, InputRichBlockPullQuotation $inputrichblockpullquotation = null, InputRichBlockCollage $inputrichblockcollage = null, InputRichBlockSlideshow $inputrichblockslideshow = null, InputRichBlockTable $inputrichblocktable = null, InputRichBlockDetails $inputrichblockdetails = null, InputRichBlockMap $inputrichblockmap = null, InputRichBlockButtons $inputrichblockbuttons = null, InputRichBlockAnimation $inputrichblockanimation = null, InputRichBlockAudio $inputrichblockaudio = null, InputRichBlockDocument $inputrichblockdocument = null, InputRichBlockPhoto $inputrichblockphoto = null, InputRichBlockVideo $inputrichblockvideo = null, InputRichBlockVoiceNote $inputrichblockvoicenote = null, InputRichBlockThinking $inputrichblockthinking = null): self
    {
        $args = get_defined_vars();
        $updates = array_filter($args, fn($v) => $v !== null);
        return new self($updates);
    }

    public function __call(string $name, array $arguments)
    {
        $this->{$name} = array_shift($arguments);
        return $this;
    }

    protected function _output(int $type = Telegram::OUTPUT_JSON): object|array|string
    {
        $output = [];
        $r = new \ReflectionClass(static::class);
        $union = $r->hasConstant('_UNION_BASE');

        $normalize = function ($value) use (&$normalize) {
            if (is_object($value) && method_exists($value, '_output'))
                return (fn() => $this->_output(Telegram::OUTPUT_ARRAY))->bindTo($value, $value)();
            if (is_array($value)) {
                $items = [];
                foreach ($value as $key => $item)
                    $items[$key] = $normalize($item);
                return $items;
            }
            return $value;
        };

        foreach ($r->getProperties(\ReflectionProperty::IS_PUBLIC) as $property):
            $name = $property->getName();
            if (!isset($this->{$name}))
                continue;

            $raw = $property->getValue($this);
            $value = $normalize($raw);

            // A union base variant property represents the payload itself, so
            // its fields replace the variant key.
            if ($union && is_object($raw) && is_array($value)
                && $name === strtolower((new \ReflectionClass($raw))->getShortName())):
                $output += $value;
                continue;
            endif;

            $output[$name] = $value;
        endforeach;
        return Statics::output($output, $type);
    }
}