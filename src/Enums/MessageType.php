<?php

namespace Namu\WireChat\Enums;

enum MessageType: string
{
    case TEXT = 'text';
    case ATTACHMENT = 'attachment';
    case VOICE = 'voice';
}
