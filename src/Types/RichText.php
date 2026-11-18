<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object represents a rich formatted text. Currently, it can be either a String for plain text, an Array of <a href="https://core.telegram.org/bots/api#richtext">RichText</a>, or any of the following types:
 * @method self richtextbold(RichTextBold $value)
 * @method self richtextitalic(RichTextItalic $value)
 * @method self richtextunderline(RichTextUnderline $value)
 * @method self richtextstrikethrough(RichTextStrikethrough $value)
 * @method self richtextspoiler(RichTextSpoiler $value)
 * @method self richtextdatetime(RichTextDateTime $value)
 * @method self richtexttextmention(RichTextTextMention $value)
 * @method self richtextsubscript(RichTextSubscript $value)
 * @method self richtextsuperscript(RichTextSuperscript $value)
 * @method self richtextmarked(RichTextMarked $value)
 * @method self richtextcode(RichTextCode $value)
 * @method self richtextcustomemoji(RichTextCustomEmoji $value)
 * @method self richtextmathematicalexpression(RichTextMathematicalExpression $value)
 * @method self richtexturl(RichTextUrl $value)
 * @method self richtextemailaddress(RichTextEmailAddress $value)
 * @method self richtextphonenumber(RichTextPhoneNumber $value)
 * @method self richtextbankcardnumber(RichTextBankCardNumber $value)
 * @method self richtexttonaddress(RichTextTonAddress $value)
 * @method self richtextmention(RichTextMention $value)
 * @method self richtexthashtag(RichTextHashtag $value)
 * @method self richtextcashtag(RichTextCashtag $value)
 * @method self richtextbotcommand(RichTextBotCommand $value)
 * @method self richtextbutton(RichTextButton $value)
 * @method self richtextanchor(RichTextAnchor $value)
 * @method self richtextanchorlink(RichTextAnchorLink $value)
 * @method self richtextreference(RichTextReference $value)
 * @method self richtextreferencelink(RichTextReferenceLink $value)
 */
class RichText
{
    protected const _UNION_BASE = true;
    /** @var RichTextBold */
    public RichTextBold $richtextbold;
    /** @var RichTextItalic */
    public RichTextItalic $richtextitalic;
    /** @var RichTextUnderline */
    public RichTextUnderline $richtextunderline;
    /** @var RichTextStrikethrough */
    public RichTextStrikethrough $richtextstrikethrough;
    /** @var RichTextSpoiler */
    public RichTextSpoiler $richtextspoiler;
    /** @var RichTextDateTime */
    public RichTextDateTime $richtextdatetime;
    /** @var RichTextTextMention */
    public RichTextTextMention $richtexttextmention;
    /** @var RichTextSubscript */
    public RichTextSubscript $richtextsubscript;
    /** @var RichTextSuperscript */
    public RichTextSuperscript $richtextsuperscript;
    /** @var RichTextMarked */
    public RichTextMarked $richtextmarked;
    /** @var RichTextCode */
    public RichTextCode $richtextcode;
    /** @var RichTextCustomEmoji */
    public RichTextCustomEmoji $richtextcustomemoji;
    /** @var RichTextMathematicalExpression */
    public RichTextMathematicalExpression $richtextmathematicalexpression;
    /** @var RichTextUrl */
    public RichTextUrl $richtexturl;
    /** @var RichTextEmailAddress */
    public RichTextEmailAddress $richtextemailaddress;
    /** @var RichTextPhoneNumber */
    public RichTextPhoneNumber $richtextphonenumber;
    /** @var RichTextBankCardNumber */
    public RichTextBankCardNumber $richtextbankcardnumber;
    /** @var RichTextTonAddress */
    public RichTextTonAddress $richtexttonaddress;
    /** @var RichTextMention */
    public RichTextMention $richtextmention;
    /** @var RichTextHashtag */
    public RichTextHashtag $richtexthashtag;
    /** @var RichTextCashtag */
    public RichTextCashtag $richtextcashtag;
    /** @var RichTextBotCommand */
    public RichTextBotCommand $richtextbotcommand;
    /** @var RichTextButton */
    public RichTextButton $richtextbutton;
    /** @var RichTextAnchor */
    public RichTextAnchor $richtextanchor;
    /** @var RichTextAnchorLink */
    public RichTextAnchorLink $richtextanchorlink;
    /** @var RichTextReference */
    public RichTextReference $richtextreference;
    /** @var RichTextReferenceLink */
    public RichTextReferenceLink $richtextreferencelink;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * This object represents a rich formatted text. Currently, it can be either a String for plain text, an Array of <a href="https://core.telegram.org/bots/api#richtext">RichText</a>, or any of the following types:
     * @param RichTextBold|null $richtextbold
     * @param RichTextItalic|null $richtextitalic
     * @param RichTextUnderline|null $richtextunderline
     * @param RichTextStrikethrough|null $richtextstrikethrough
     * @param RichTextSpoiler|null $richtextspoiler
     * @param RichTextDateTime|null $richtextdatetime
     * @param RichTextTextMention|null $richtexttextmention
     * @param RichTextSubscript|null $richtextsubscript
     * @param RichTextSuperscript|null $richtextsuperscript
     * @param RichTextMarked|null $richtextmarked
     * @param RichTextCode|null $richtextcode
     * @param RichTextCustomEmoji|null $richtextcustomemoji
     * @param RichTextMathematicalExpression|null $richtextmathematicalexpression
     * @param RichTextUrl|null $richtexturl
     * @param RichTextEmailAddress|null $richtextemailaddress
     * @param RichTextPhoneNumber|null $richtextphonenumber
     * @param RichTextBankCardNumber|null $richtextbankcardnumber
     * @param RichTextTonAddress|null $richtexttonaddress
     * @param RichTextMention|null $richtextmention
     * @param RichTextHashtag|null $richtexthashtag
     * @param RichTextCashtag|null $richtextcashtag
     * @param RichTextBotCommand|null $richtextbotcommand
     * @param RichTextButton|null $richtextbutton
     * @param RichTextAnchor|null $richtextanchor
     * @param RichTextAnchorLink|null $richtextanchorlink
     * @param RichTextReference|null $richtextreference
     * @param RichTextReferenceLink|null $richtextreferencelink
     */
    public static function make(RichTextBold $richtextbold = null, RichTextItalic $richtextitalic = null, RichTextUnderline $richtextunderline = null, RichTextStrikethrough $richtextstrikethrough = null, RichTextSpoiler $richtextspoiler = null, RichTextDateTime $richtextdatetime = null, RichTextTextMention $richtexttextmention = null, RichTextSubscript $richtextsubscript = null, RichTextSuperscript $richtextsuperscript = null, RichTextMarked $richtextmarked = null, RichTextCode $richtextcode = null, RichTextCustomEmoji $richtextcustomemoji = null, RichTextMathematicalExpression $richtextmathematicalexpression = null, RichTextUrl $richtexturl = null, RichTextEmailAddress $richtextemailaddress = null, RichTextPhoneNumber $richtextphonenumber = null, RichTextBankCardNumber $richtextbankcardnumber = null, RichTextTonAddress $richtexttonaddress = null, RichTextMention $richtextmention = null, RichTextHashtag $richtexthashtag = null, RichTextCashtag $richtextcashtag = null, RichTextBotCommand $richtextbotcommand = null, RichTextButton $richtextbutton = null, RichTextAnchor $richtextanchor = null, RichTextAnchorLink $richtextanchorlink = null, RichTextReference $richtextreference = null, RichTextReferenceLink $richtextreferencelink = null): self
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