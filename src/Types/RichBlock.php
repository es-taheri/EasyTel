<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object represents a block in a rich formatted message. Currently, it can be any of the following types:
 * @method self richblockparagraph(RichBlockParagraph $value)
 * @method self richblocksectionheading(RichBlockSectionHeading $value)
 * @method self richblockpreformatted(RichBlockPreformatted $value)
 * @method self richblockfooter(RichBlockFooter $value)
 * @method self richblockdivider(RichBlockDivider $value)
 * @method self richblockmathematicalexpression(RichBlockMathematicalExpression $value)
 * @method self richblockanchor(RichBlockAnchor $value)
 * @method self richblocklist(RichBlockList $value)
 * @method self richblockblockquotation(RichBlockBlockQuotation $value)
 * @method self richblockexpandableblockquotation(RichBlockExpandableBlockQuotation $value)
 * @method self richblockpullquotation(RichBlockPullQuotation $value)
 * @method self richblockcollage(RichBlockCollage $value)
 * @method self richblockslideshow(RichBlockSlideshow $value)
 * @method self richblocktable(RichBlockTable $value)
 * @method self richblockdetails(RichBlockDetails $value)
 * @method self richblockmap(RichBlockMap $value)
 * @method self richblockbuttons(RichBlockButtons $value)
 * @method self richblockanimation(RichBlockAnimation $value)
 * @method self richblockaudio(RichBlockAudio $value)
 * @method self richblockdocument(RichBlockDocument $value)
 * @method self richblockphoto(RichBlockPhoto $value)
 * @method self richblockvideo(RichBlockVideo $value)
 * @method self richblockvoicenote(RichBlockVoiceNote $value)
 * @method self richblockthinking(RichBlockThinking $value)
 */
class RichBlock
{
    protected const _UNION_BASE = true;
    /** @var RichBlockParagraph */
    public RichBlockParagraph $richblockparagraph;
    /** @var RichBlockSectionHeading */
    public RichBlockSectionHeading $richblocksectionheading;
    /** @var RichBlockPreformatted */
    public RichBlockPreformatted $richblockpreformatted;
    /** @var RichBlockFooter */
    public RichBlockFooter $richblockfooter;
    /** @var RichBlockDivider */
    public RichBlockDivider $richblockdivider;
    /** @var RichBlockMathematicalExpression */
    public RichBlockMathematicalExpression $richblockmathematicalexpression;
    /** @var RichBlockAnchor */
    public RichBlockAnchor $richblockanchor;
    /** @var RichBlockList */
    public RichBlockList $richblocklist;
    /** @var RichBlockBlockQuotation */
    public RichBlockBlockQuotation $richblockblockquotation;
    /** @var RichBlockExpandableBlockQuotation */
    public RichBlockExpandableBlockQuotation $richblockexpandableblockquotation;
    /** @var RichBlockPullQuotation */
    public RichBlockPullQuotation $richblockpullquotation;
    /** @var RichBlockCollage */
    public RichBlockCollage $richblockcollage;
    /** @var RichBlockSlideshow */
    public RichBlockSlideshow $richblockslideshow;
    /** @var RichBlockTable */
    public RichBlockTable $richblocktable;
    /** @var RichBlockDetails */
    public RichBlockDetails $richblockdetails;
    /** @var RichBlockMap */
    public RichBlockMap $richblockmap;
    /** @var RichBlockButtons */
    public RichBlockButtons $richblockbuttons;
    /** @var RichBlockAnimation */
    public RichBlockAnimation $richblockanimation;
    /** @var RichBlockAudio */
    public RichBlockAudio $richblockaudio;
    /** @var RichBlockDocument */
    public RichBlockDocument $richblockdocument;
    /** @var RichBlockPhoto */
    public RichBlockPhoto $richblockphoto;
    /** @var RichBlockVideo */
    public RichBlockVideo $richblockvideo;
    /** @var RichBlockVoiceNote */
    public RichBlockVoiceNote $richblockvoicenote;
    /** @var RichBlockThinking */
    public RichBlockThinking $richblockthinking;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * This object represents a block in a rich formatted message. Currently, it can be any of the following types:
     * @param RichBlockParagraph|null $richblockparagraph
     * @param RichBlockSectionHeading|null $richblocksectionheading
     * @param RichBlockPreformatted|null $richblockpreformatted
     * @param RichBlockFooter|null $richblockfooter
     * @param RichBlockDivider|null $richblockdivider
     * @param RichBlockMathematicalExpression|null $richblockmathematicalexpression
     * @param RichBlockAnchor|null $richblockanchor
     * @param RichBlockList|null $richblocklist
     * @param RichBlockBlockQuotation|null $richblockblockquotation
     * @param RichBlockExpandableBlockQuotation|null $richblockexpandableblockquotation
     * @param RichBlockPullQuotation|null $richblockpullquotation
     * @param RichBlockCollage|null $richblockcollage
     * @param RichBlockSlideshow|null $richblockslideshow
     * @param RichBlockTable|null $richblocktable
     * @param RichBlockDetails|null $richblockdetails
     * @param RichBlockMap|null $richblockmap
     * @param RichBlockButtons|null $richblockbuttons
     * @param RichBlockAnimation|null $richblockanimation
     * @param RichBlockAudio|null $richblockaudio
     * @param RichBlockDocument|null $richblockdocument
     * @param RichBlockPhoto|null $richblockphoto
     * @param RichBlockVideo|null $richblockvideo
     * @param RichBlockVoiceNote|null $richblockvoicenote
     * @param RichBlockThinking|null $richblockthinking
     */
    public static function make(RichBlockParagraph $richblockparagraph = null, RichBlockSectionHeading $richblocksectionheading = null, RichBlockPreformatted $richblockpreformatted = null, RichBlockFooter $richblockfooter = null, RichBlockDivider $richblockdivider = null, RichBlockMathematicalExpression $richblockmathematicalexpression = null, RichBlockAnchor $richblockanchor = null, RichBlockList $richblocklist = null, RichBlockBlockQuotation $richblockblockquotation = null, RichBlockExpandableBlockQuotation $richblockexpandableblockquotation = null, RichBlockPullQuotation $richblockpullquotation = null, RichBlockCollage $richblockcollage = null, RichBlockSlideshow $richblockslideshow = null, RichBlockTable $richblocktable = null, RichBlockDetails $richblockdetails = null, RichBlockMap $richblockmap = null, RichBlockButtons $richblockbuttons = null, RichBlockAnimation $richblockanimation = null, RichBlockAudio $richblockaudio = null, RichBlockDocument $richblockdocument = null, RichBlockPhoto $richblockphoto = null, RichBlockVideo $richblockvideo = null, RichBlockVoiceNote $richblockvoicenote = null, RichBlockThinking $richblockthinking = null): self
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