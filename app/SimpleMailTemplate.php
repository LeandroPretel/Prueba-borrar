<?php

namespace App;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SimpleMailTemplate extends Mailable
{
    use Queueable;
    use SerializesModels;

    protected $title;
    protected $subtitle;
    protected $text;
    protected $url;
    protected $urlAction;
    protected $logoUrl;
    protected $themeColor;
    protected $reportFile;
    protected $replyToMail;

    /**
     * Create a new message instance.
     *
     * @param string $title
     * @param string|null $subtitle
     * @param array $text
     * @param string|null $url
     * @param string|null $urlAction
     * @param string|null $logoUrl
     * @param string|null $themeColor
     * @param mixed $reportFile
     * @param string|null $replyToMail
     */
    public function __construct(string $title, ?string $subtitle, $reportFile, array $text = [], ?string $url = null, ?string $urlAction = null, ?string $logoUrl = null,
                                ?string $themeColor = null, ?string $replyToMail = null)
    {
        $this->title = $title;
        $this->subtitle = $subtitle;
        $this->text = $text;
        $this->url = $url;
        $this->urlAction = $urlAction;
        $this->logoUrl = $logoUrl;
        $this->themeColor = $themeColor;
        $this->reportFile = $reportFile;
        $this->replyToMail = $replyToMail;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): self
    {
        $view = $this->subject($this->title)->view('mail.simple-mail')->with([
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'text' => $this->text,
            'url' => $this->url,
            'urlAction' => $this->urlAction,
            'logoUrl' => $this->logoUrl,
            'themeColor' => $this->themeColor,
        ]);

        $view->attachData($this->reportFile, $this->title . '.pdf', [
            'mime' => 'application/pdf',
        ]);

        if ($this->replyToMail) {
            $view->replyTo($this->replyToMail);
        }

        return $view;
    }
}
