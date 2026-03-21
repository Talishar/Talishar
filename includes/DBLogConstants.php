<?php

// DB Connection log constants — each maps to a unique SHMOP key for connection counting.
// Keys start at 1 and increment sequentially. Add new entries at the end.

const DBL_ACCOUNT_SESSION_API          = 1;
const DBL_DELETE_ACCOUNT_API           = 2;
const DBL_GENERATE_AUTH_TOKEN_API      = 3;
const DBL_METAFY_LOGIN_API             = 4;
const DBL_METAFY_SIGNUP_API            = 5;
const DBL_PASSWORD_RESET_REQUEST_API   = 6;
const DBL_RESET_PASSWORD               = 7;
const DBL_SIGNUP_API                   = 8;
const DBL_BLOCKED_USERS_API            = 9;
const DBL_CHECK_PATREON_API            = 10;
const DBL_DELETE_DECK_API              = 11;
const DBL_FRIEND_LIST_API              = 12;
const DBL_GET_COSMETICS                = 13;
const DBL_GET_GAME_LIST                = 14;
const DBL_GET_INITIAL_GAME_DATA_API    = 15;
const DBL_GET_MOD_PAGE_DATA            = 16;
const DBL_GET_SYSTEM_MESSAGE           = 17;
const DBL_METAFY_API                   = 18;
const DBL_PRIVATE_MESSAGING_API        = 19;
const DBL_REFRESH_METAFY_COMMUNITIES   = 20;
const DBL_SEARCH_USERNAMES             = 21;
const DBL_SYNC_METAFY_SUBSCRIBERS      = 22;
const DBL_SYSTEM_MESSAGE_API           = 23;
const DBL_UPDATE_FAVORITE_DECK         = 24;
const DBL_USER_PROFILE_API             = 25;
const DBL_USERNAME_MODERATION          = 26;
const DBL_BUILD_GAME_STATE             = 27;
const DBL_CARD_EDITOR_DATABASE         = 28;
const DBL_SETUP_PRIVATE_MESSAGES_TABLE = 29;
const DBL_BLOCK_USER                   = 30;
const DBL_METAFY_HELPER                = 31;
const DBL_FUNCTIONS                    = 32;
const DBL_RESET_REQUEST                = 33;
const DBL_MOD_PAGE                     = 34;
const DBL_PATREON_DEBUG                = 35;

// Total number of tracked call sites — used by the report page to iterate all keys.
const DBL_MAX_KEY = 35;

// Human-readable labels for the report page, indexed by constant value.
const DBL_LABELS = [
    1  => 'AccountFiles/AccountSessionAPI.php',
    2  => 'AccountFiles/DeleteAccountAPI.php',
    3  => 'AccountFiles/GenerateAuthTokenAPI.php',
    4  => 'AccountFiles/MetafyLoginAPI.php',
    5  => 'AccountFiles/MetafySignupAPI.php',
    6  => 'AccountFiles/PasswordResetRequestAPI.php',
    7  => 'AccountFiles/ResetPassword.php',
    8  => 'AccountFiles/SignupAPI.php',
    9  => 'APIs/BlockedUsersAPI.php',
    10 => 'APIs/CheckPatreonAPI.php',
    11 => 'APIs/DeleteDeckAPI.php',
    12 => 'APIs/FriendListAPI.php',
    13 => 'APIs/GetCosmetics.php',
    14 => 'APIs/GetGameList.php',
    15 => 'APIs/GetInitialGameDataAPI.php',
    16 => 'APIs/GetModPageData.php',
    17 => 'APIs/GetSystemMessage.php',
    18 => 'APIs/MetafyAPI.php',
    19 => 'APIs/PrivateMessagingAPI.php',
    20 => 'APIs/RefreshMetafyCommunities.php',
    21 => 'APIs/SearchUsernames.php',
    22 => 'APIs/SyncMetafySubscribers.php',
    23 => 'APIs/SystemMessageAPI.php',
    24 => 'APIs/UpdateFavoriteDeck.php',
    25 => 'APIs/UserProfileAPI.php',
    26 => 'APIs/UsernameModeration.php',
    27 => 'BuildGameState.php',
    28 => 'CardEditor/CardEditorDatabase.php',
    29 => 'SetupPrivateMessagesTable.php',
    30 => 'includes/BlockUser.php',
    31 => 'includes/MetafyHelper.php',
    32 => 'includes/functions.inc.php',
    33 => 'includes/reset-request.inc.php',
    34 => 'zzModPage.php',
    35 => 'zzPatreonDebug.php',
];
