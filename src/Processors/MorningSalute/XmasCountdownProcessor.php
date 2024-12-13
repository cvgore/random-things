<?php

namespace Cvgore\RandomThings\Processors\MorningSalute;

final readonly class XmasCountdownProcessor implements MorningSaluteProcessor
{
    public function getPlaceholder(): string
    {
        return '@xmasCountdown';
    }

    public function generate(): string
    {
        $now = new \DateTime();
        $christmas = new \DateTime('December 24');
        $newYear = new \DateTime('December 31');

        $daysUntilChristmas = $now->diff($christmas)->days;
        $daysUntilNewYear = $now->diff($newYear)->days;

        if ($now->format('Y-m-d') === $christmas->format('Y-m-d')) {
            return "**🎄✨ Wesołych Świąt! ✨🎄**\n" .
                "Chciałbym wam życzyć dobrych prezetów, fajnych tam wiecie, tego. A tak ogólnie to nawzajem\n".
                "https://www.youtube.com/watch?v=gRdQZMFT0zg";
        } elseif ($now > $christmas && $now < $newYear) {
            return "**😢 Święta święta i po...chuj ja żyje 😭**\n" .
                "🎉 Ale spokojnie, do sylwka już tylko **{$daysUntilNewYear} dni**! 🎆✨";
        } elseif ($now->format('Y-m-d') === $newYear->format('Y-m-d')) {
            return "**🎆🎉 Sylwester majstry! 🎉🎆**\n" .
                "🎇🌟 Hepi nju jer 🌟🎇\n" .
                "Pamiętajcie o szampanie: <https://www.youtube.com/watch?v=bUwGXOcZVNg>"
                ;
        } elseif ($now->format('m-d') === '01-01') {
            return "**🥳🎉 Szczęśliwego Nowego Roku! 🎉🥳**\n" .
                "🎆✨ Niech ten rok będzie pełen sukcesów i radości! Dobra przyznaje zachlałem. ✨🎆";
        } else {
            return "**🎄Święta zaraz pany 🎄**\n" .
                "🎅📅 Pozostało **{$daysUntilChristmas} dni**! 📅🎅\n" .
                "🎁✨ Handy Playlista świąteczna ➡️ https://www.youtube.com/watch?v=Y3tfbmdbhTw ✨🎁";
        }
    }
}