<?php

namespace App\Services\Comment;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Support\Str;

class MentionParserService
{
    /**
     * Parse mentioned users in comment text scoped to organization.
     *
     * @return array<int, User>
     */
    public function parseMentions(string $content, Organization $organization): array
    {
        preg_match_all('/@([a-zA-Z0-9_-]+)/', $content, $matches);

        if (empty($matches[1])) {
            return [];
        }

        $mentionedHandles = array_map('strtolower', array_unique($matches[1]));
        $members = $organization->users()->get();
        /** @var array<int, User> $matchedUsers */
        $matchedUsers = [];

        foreach ($members as $user) {
            /** @var User $user */
            $slugName = Str::slug($user->name);
            $compactName = strtolower(str_replace(' ', '', $user->name));

            foreach ($mentionedHandles as $handle) {
                if ($handle === $slugName || $handle === $compactName) {
                    $matchedUsers[] = $user;
                    break;
                }
            }
        }

        return $matchedUsers;
    }

    /**
     * Highlight mentions in text by wrapping them in Tailwind styled tags.
     */
    public function highlightMentions(string $content, Organization $organization): string
    {
        $users = $this->parseMentions($content, $organization);

        foreach ($users as $user) {
            $slugName = Str::slug($user->name);
            $compactName = strtolower(str_replace(' ', '', $user->name));

            // Replace both forms
            $content = preg_replace(
                '/@('.preg_quote($slugName, '/').'|'.preg_quote($compactName, '/').')\b/i',
                '<span class="text-orange-500 font-bold">@$1</span>',
                $content
            );
        }

        return $content;
    }
}
