# Codebase Architecture Skeleton Map

> Generated: 2026-11-09 01:50:28
> This document provides a high-speed instant lookup index of all Models, Repositories, Services, Controllers, and public method signatures.

## Table of Contents
- [Models](#models)
- [Repositories (Interfaces)](#repositories-interfaces-)
- [Repositories (Eloquent)](#repositories-eloquent-)
- [Services](#services)
- [Controllers (Frontend)](#controllers-frontend-)
- [Controllers (Admin)](#controllers-admin-)
- [Controllers (Root & Auth)](#controllers-root-auth-)
- [Requests](#requests)
- [Traits](#traits)

---

## Models

### `class About`
- **Namespace**: `\App\Models`
- **Hierarchy**: `About extends Model implements UrlRoutable, Stringable, QueueableEntity, JsonSerializable, Jsonable, HasBroadcastChannel, CanBeEscapedWhenCastToString, ArrayAccess, Arrayable`
- **Table**: `tp_abouts`
- **Fillable**: `uuid`, `name_vn`, `name_en`, `intro_vn`, `intro_en`, `content_vn`, `content_en`, `image`, `link`, `status`, `stt`, `stats`
- **Public API Methods**:
  ```php
  function getNameAttribute()
  function getIntroAttribute()
  function getContentAttribute()
  function getImageAttribute($value)
  static function all($columns = []): Illuminate\Database\Eloquent\Collection
  static function destroy($ids): int
  function newEloquentBuilder($query): Illuminate\Database\Eloquent\Builder
  function getObservableEvents()
  function fireModelEvent($event, $halt = true, $relationName = null, $ids = [], $idsAttributes = [])
  function getImageVnAttribute($value)
  function getImageEnAttribute($value)
  function getAvatarAttribute($value)
  function getLogoAttribute($value)
  function getFaviconAttribute($value)
  function getShareImageAttribute($value)
  function applyScopes(): Illuminate\Database\Eloquent\Builder|static
  function cache(array $tags = []): Illuminate\Contracts\Cache\Factory|Illuminate\Contracts\Cache\Repository
  function disableModelCaching(): static
  function flushCache(array $tags = []): void
  function isCachable(): bool
  function shouldFallbackToDatabase(): bool
  function isCacheConnectionException(Throwable $exception): bool
  function withCacheFallback(callable $operation, string $context, ?callable $fallback = null): mixed
  static function bootCachable(): void
  function newModelCachingEloquentBuilder($query): Illuminate\Database\Eloquent\Builder
  function scopeDisableCache(Illuminate\Database\Eloquent\Builder $query): Illuminate\Database\Eloquent\Builder
  function scopeWithCacheCooldownSeconds(Illuminate\Database\Eloquent\Builder $query, ?int $seconds = null): Illuminate\Database\Eloquent\Builder
  static function pivotSyncing($callback, $priority = 0)
  static function pivotSynced($callback, $priority = 0)
  static function pivotAttaching($callback, $priority = 0)
  static function pivotAttached($callback, $priority = 0)
  static function pivotDetaching($callback, $priority = 0)
  static function pivotDetached($callback, $priority = 0)
  static function pivotUpdating($callback, $priority = 0)
  static function pivotUpdated($callback, $priority = 0)
  static function factory($count = null, $state = [])
  ```

### `class Analytic`
- **Namespace**: `\App\Models`
- **Hierarchy**: `Analytic extends Model implements UrlRoutable, Stringable, QueueableEntity, JsonSerializable, Jsonable, HasBroadcastChannel, CanBeEscapedWhenCastToString, ArrayAccess, Arrayable`
- **Table**: `tp_analytics`
- **Fillable**: `visit_date`, `visit_count`
- **Public API Methods**:
  ```php
  static function factory($count = null, $state = [])
  ```

### `class Brand`
- **Namespace**: `\App\Models`
- **Hierarchy**: `Brand extends Model implements UrlRoutable, Stringable, QueueableEntity, JsonSerializable, Jsonable, HasBroadcastChannel, CanBeEscapedWhenCastToString, ArrayAccess, Arrayable`
- **Table**: `tp_brands`
- **Fillable**: `name_vn`, `name_en`, `slug_vn`, `slug_en`, `image_vn`, `image_en`, `stt`, `uuid`, `status`
- **Public API Methods**:
  ```php
  function getSlugAttribute()
  function getSlugVnAttribute()
  static function all($columns = []): Illuminate\Database\Eloquent\Collection
  static function destroy($ids): int
  function newEloquentBuilder($query): Illuminate\Database\Eloquent\Builder
  function getObservableEvents()
  function fireModelEvent($event, $halt = true, $relationName = null, $ids = [], $idsAttributes = [])
  function getImageVnAttribute($value)
  function getImageEnAttribute($value)
  function getImageAttribute($value = null)
  function getAvatarAttribute($value)
  function getLogoAttribute($value)
  function getFaviconAttribute($value)
  function getShareImageAttribute($value)
  function applyScopes(): Illuminate\Database\Eloquent\Builder|static
  function cache(array $tags = []): Illuminate\Contracts\Cache\Factory|Illuminate\Contracts\Cache\Repository
  function disableModelCaching(): static
  function flushCache(array $tags = []): void
  function isCachable(): bool
  function shouldFallbackToDatabase(): bool
  function isCacheConnectionException(Throwable $exception): bool
  function withCacheFallback(callable $operation, string $context, ?callable $fallback = null): mixed
  static function bootCachable(): void
  function newModelCachingEloquentBuilder($query): Illuminate\Database\Eloquent\Builder
  function scopeDisableCache(Illuminate\Database\Eloquent\Builder $query): Illuminate\Database\Eloquent\Builder
  function scopeWithCacheCooldownSeconds(Illuminate\Database\Eloquent\Builder $query, ?int $seconds = null): Illuminate\Database\Eloquent\Builder
  static function pivotSyncing($callback, $priority = 0)
  static function pivotSynced($callback, $priority = 0)
  static function pivotAttaching($callback, $priority = 0)
  static function pivotAttached($callback, $priority = 0)
  static function pivotDetaching($callback, $priority = 0)
  static function pivotDetached($callback, $priority = 0)
  static function pivotUpdating($callback, $priority = 0)
  static function pivotUpdated($callback, $priority = 0)
  static function factory($count = null, $state = [])
  ```

### `class CateNew`
- **Namespace**: `\App\Models`
- **Hierarchy**: `CateNew extends Model implements UrlRoutable, Stringable, QueueableEntity, JsonSerializable, Jsonable, HasBroadcastChannel, CanBeEscapedWhenCastToString, ArrayAccess, Arrayable`
- **Table**: `tp_cate_news`
- **Fillable**: `uuid`, `name_vn`, `name_en`, `slug_vn`, `slug_en`, `keyword_vn`, `keyword_en`, `description_vn`, `description_en`, `image_vn`, `image_en`, `stt`, `parent_id`
- **Eloquent Relationships**:
  - `children()`: `hasMany(CateNew::class, 'parent_id', 'id_cate_new')`
  - `news()`: `hasMany(News::class, 'category_id', 'id_cate_new')->where('status', 1)->orderBy('stt', 'asc')`
  - `activities()`: `morphMany(ActivitylogServiceProvider::determineActivityModel(), 'subject')`
- **Public API Methods**:
  ```php
  function getSlugAttribute()
  function getNameAttribute()
  function getImageAttribute()
  function getKeywordAttribute()
  function getKeywordsAttribute()
  function getDescriptionAttribute()
  function children()
  function news()
  static function all($columns = []): Illuminate\Database\Eloquent\Collection
  static function destroy($ids): int
  function newEloquentBuilder($query): Illuminate\Database\Eloquent\Builder
  function getObservableEvents()
  function fireModelEvent($event, $halt = true, $relationName = null, $ids = [], $idsAttributes = [])
  function getImageVnAttribute($value)
  function getImageEnAttribute($value)
  function getAvatarAttribute($value)
  function getLogoAttribute($value)
  function getFaviconAttribute($value)
  function getShareImageAttribute($value)
  function getActivitylogOptions(): Spatie\Activitylog\LogOptions
  static function addLogChange(Spatie\Activitylog\Contracts\LoggablePipe $pipe): void
  function isLogEmpty(array $changes): bool
  function disableLogging(): self
  function enableLogging(): self
  function activities(): Illuminate\Database\Eloquent\Relations\MorphMany
  function getDescriptionForEvent(string $eventName): string
  function getLogNameToUse(): ?string
  function attributesToBeLogged(): array
  function shouldLogUnguarded(): bool
  function attributeValuesToBeLogged(string $processingEvent): array
  static function logChanges(Illuminate\Database\Eloquent\Model $model): array
  function applyScopes(): Illuminate\Database\Eloquent\Builder|static
  function cache(array $tags = []): Illuminate\Contracts\Cache\Factory|Illuminate\Contracts\Cache\Repository
  function disableModelCaching(): static
  function flushCache(array $tags = []): void
  function isCachable(): bool
  function shouldFallbackToDatabase(): bool
  function isCacheConnectionException(Throwable $exception): bool
  function withCacheFallback(callable $operation, string $context, ?callable $fallback = null): mixed
  static function bootCachable(): void
  function newModelCachingEloquentBuilder($query): Illuminate\Database\Eloquent\Builder
  function scopeDisableCache(Illuminate\Database\Eloquent\Builder $query): Illuminate\Database\Eloquent\Builder
  function scopeWithCacheCooldownSeconds(Illuminate\Database\Eloquent\Builder $query, ?int $seconds = null): Illuminate\Database\Eloquent\Builder
  static function pivotSyncing($callback, $priority = 0)
  static function pivotSynced($callback, $priority = 0)
  static function pivotAttaching($callback, $priority = 0)
  static function pivotAttached($callback, $priority = 0)
  static function pivotDetaching($callback, $priority = 0)
  static function pivotDetached($callback, $priority = 0)
  static function pivotUpdating($callback, $priority = 0)
  static function pivotUpdated($callback, $priority = 0)
  static function factory($count = null, $state = [])
  ```

### `class CateProduct`
- **Namespace**: `\App\Models`
- **Hierarchy**: `CateProduct extends Model implements UrlRoutable, Stringable, QueueableEntity, JsonSerializable, Jsonable, HasBroadcastChannel, CanBeEscapedWhenCastToString, ArrayAccess, Arrayable`
- **Table**: `tp_cate_products`
- **Fillable**: `uuid`, `name_vn`, `name_en`, `slug_vn`, `slug_en`, `keyword_vn`, `keyword_en`, `description_vn`, `description_en`, `image_vn`, `image_en`, `stt`, `parent_id`
- **Eloquent Relationships**:
  - `children()`: `hasMany(CateProduct::class, 'parent_id', 'id_cate_product')`
  - `products()`: `hasMany(Product::class, 'category_id', 'id_cate_product')->where('status', 1) ->select('id_product','uuid', 'name_vn', 'slug_vn', 'slug_en', 'price','image_vn', 'image_en', 'intro_vn', 'category_id', 'status','hot','stt', 'created_at') ->orderBy('stt', 'asc')`
  - `parent()`: `belongsTo(CateProduct::class, 'parent_id', 'id_cate_product')`
  - `activities()`: `morphMany(ActivitylogServiceProvider::determineActivityModel(), 'subject')`
- **Public API Methods**:
  ```php
  function getSlugAttribute()
  function getNameAttribute()
  function getImageAttribute()
  function getKeywordAttribute()
  function getKeywordsAttribute()
  function getDescriptionAttribute()
  function children()
  function products()
  function parent()
  static function all($columns = []): Illuminate\Database\Eloquent\Collection
  static function destroy($ids): int
  function newEloquentBuilder($query): Illuminate\Database\Eloquent\Builder
  function getObservableEvents()
  function fireModelEvent($event, $halt = true, $relationName = null, $ids = [], $idsAttributes = [])
  function getImageVnAttribute($value)
  function getImageEnAttribute($value)
  function getAvatarAttribute($value)
  function getLogoAttribute($value)
  function getFaviconAttribute($value)
  function getShareImageAttribute($value)
  function getActivitylogOptions(): Spatie\Activitylog\LogOptions
  static function addLogChange(Spatie\Activitylog\Contracts\LoggablePipe $pipe): void
  function isLogEmpty(array $changes): bool
  function disableLogging(): self
  function enableLogging(): self
  function activities(): Illuminate\Database\Eloquent\Relations\MorphMany
  function getDescriptionForEvent(string $eventName): string
  function getLogNameToUse(): ?string
  function attributesToBeLogged(): array
  function shouldLogUnguarded(): bool
  function attributeValuesToBeLogged(string $processingEvent): array
  static function logChanges(Illuminate\Database\Eloquent\Model $model): array
  function applyScopes(): Illuminate\Database\Eloquent\Builder|static
  function cache(array $tags = []): Illuminate\Contracts\Cache\Factory|Illuminate\Contracts\Cache\Repository
  function disableModelCaching(): static
  function flushCache(array $tags = []): void
  function isCachable(): bool
  function shouldFallbackToDatabase(): bool
  function isCacheConnectionException(Throwable $exception): bool
  function withCacheFallback(callable $operation, string $context, ?callable $fallback = null): mixed
  static function bootCachable(): void
  function newModelCachingEloquentBuilder($query): Illuminate\Database\Eloquent\Builder
  function scopeDisableCache(Illuminate\Database\Eloquent\Builder $query): Illuminate\Database\Eloquent\Builder
  function scopeWithCacheCooldownSeconds(Illuminate\Database\Eloquent\Builder $query, ?int $seconds = null): Illuminate\Database\Eloquent\Builder
  static function pivotSyncing($callback, $priority = 0)
  static function pivotSynced($callback, $priority = 0)
  static function pivotAttaching($callback, $priority = 0)
  static function pivotAttached($callback, $priority = 0)
  static function pivotDetaching($callback, $priority = 0)
  static function pivotDetached($callback, $priority = 0)
  static function pivotUpdating($callback, $priority = 0)
  static function pivotUpdated($callback, $priority = 0)
  static function factory($count = null, $state = [])
  ```

### `class CateProject`
- **Namespace**: `\App\Models`
- **Hierarchy**: `CateProject extends Model implements UrlRoutable, Stringable, QueueableEntity, JsonSerializable, Jsonable, HasBroadcastChannel, CanBeEscapedWhenCastToString, ArrayAccess, Arrayable`
- **Table**: `tp_cate_projects`
- **Fillable**: `uuid`, `name_vn`, `name_en`, `slug_vn`, `slug_en`, `keyword_vn`, `keyword_en`, `description_vn`, `description_en`, `image_vn`, `image_en`, `stt`, `parent_id`, `status`, `home`
- **Eloquent Relationships**:
  - `children()`: `hasMany(CateProject::class, 'parent_id', 'id_cate_project')`
  - `projects()`: `hasMany(Project::class, 'category_id', 'id_cate_project')->where('status', 1)->orderBy('stt', 'asc')`
- **Public API Methods**:
  ```php
  function getSlugAttribute()
  function getNameAttribute()
  function getImageAttribute()
  function getKeywordAttribute()
  function getKeywordsAttribute()
  function getDescriptionAttribute()
  function children()
  function projects()
  static function all($columns = []): Illuminate\Database\Eloquent\Collection
  static function destroy($ids): int
  function newEloquentBuilder($query): Illuminate\Database\Eloquent\Builder
  function getObservableEvents()
  function fireModelEvent($event, $halt = true, $relationName = null, $ids = [], $idsAttributes = [])
  function getImageVnAttribute($value)
  function getImageEnAttribute($value)
  function getAvatarAttribute($value)
  function getLogoAttribute($value)
  function getFaviconAttribute($value)
  function getShareImageAttribute($value)
  function applyScopes(): Illuminate\Database\Eloquent\Builder|static
  function cache(array $tags = []): Illuminate\Contracts\Cache\Factory|Illuminate\Contracts\Cache\Repository
  function disableModelCaching(): static
  function flushCache(array $tags = []): void
  function isCachable(): bool
  function shouldFallbackToDatabase(): bool
  function isCacheConnectionException(Throwable $exception): bool
  function withCacheFallback(callable $operation, string $context, ?callable $fallback = null): mixed
  static function bootCachable(): void
  function newModelCachingEloquentBuilder($query): Illuminate\Database\Eloquent\Builder
  function scopeDisableCache(Illuminate\Database\Eloquent\Builder $query): Illuminate\Database\Eloquent\Builder
  function scopeWithCacheCooldownSeconds(Illuminate\Database\Eloquent\Builder $query, ?int $seconds = null): Illuminate\Database\Eloquent\Builder
  static function pivotSyncing($callback, $priority = 0)
  static function pivotSynced($callback, $priority = 0)
  static function pivotAttaching($callback, $priority = 0)
  static function pivotAttached($callback, $priority = 0)
  static function pivotDetaching($callback, $priority = 0)
  static function pivotDetached($callback, $priority = 0)
  static function pivotUpdating($callback, $priority = 0)
  static function pivotUpdated($callback, $priority = 0)
  static function factory($count = null, $state = [])
  ```

### `class CateService`
- **Namespace**: `\App\Models`
- **Hierarchy**: `CateService extends Model implements UrlRoutable, Stringable, QueueableEntity, JsonSerializable, Jsonable, HasBroadcastChannel, CanBeEscapedWhenCastToString, ArrayAccess, Arrayable`
- **Table**: `tp_cate_services`
- **Fillable**: `uuid`, `name_vn`, `name_en`, `slug_vn`, `slug_en`, `keyword_vn`, `keyword_en`, `description_vn`, `description_en`, `image_vn`, `image_en`, `stt`, `parent_id`
- **Eloquent Relationships**:
  - `children()`: `hasMany(CateService::class, 'parent_id', 'id_cate_service')`
  - `services()`: `hasMany(Service::class, 'category_id', 'id_cate_service')->where('status', 1)->orderBy('stt', 'asc')`
- **Public API Methods**:
  ```php
  function getSlugAttribute()
  function getNameAttribute()
  function getImageAttribute()
  function getKeywordAttribute()
  function getKeywordsAttribute()
  function getDescriptionAttribute()
  function children()
  function services()
  static function all($columns = []): Illuminate\Database\Eloquent\Collection
  static function destroy($ids): int
  function newEloquentBuilder($query): Illuminate\Database\Eloquent\Builder
  function getObservableEvents()
  function fireModelEvent($event, $halt = true, $relationName = null, $ids = [], $idsAttributes = [])
  function getImageVnAttribute($value)
  function getImageEnAttribute($value)
  function getAvatarAttribute($value)
  function getLogoAttribute($value)
  function getFaviconAttribute($value)
  function getShareImageAttribute($value)
  function applyScopes(): Illuminate\Database\Eloquent\Builder|static
  function cache(array $tags = []): Illuminate\Contracts\Cache\Factory|Illuminate\Contracts\Cache\Repository
  function disableModelCaching(): static
  function flushCache(array $tags = []): void
  function isCachable(): bool
  function shouldFallbackToDatabase(): bool
  function isCacheConnectionException(Throwable $exception): bool
  function withCacheFallback(callable $operation, string $context, ?callable $fallback = null): mixed
  static function bootCachable(): void
  function newModelCachingEloquentBuilder($query): Illuminate\Database\Eloquent\Builder
  function scopeDisableCache(Illuminate\Database\Eloquent\Builder $query): Illuminate\Database\Eloquent\Builder
  function scopeWithCacheCooldownSeconds(Illuminate\Database\Eloquent\Builder $query, ?int $seconds = null): Illuminate\Database\Eloquent\Builder
  static function pivotSyncing($callback, $priority = 0)
  static function pivotSynced($callback, $priority = 0)
  static function pivotAttaching($callback, $priority = 0)
  static function pivotAttached($callback, $priority = 0)
  static function pivotDetaching($callback, $priority = 0)
  static function pivotDetached($callback, $priority = 0)
  static function pivotUpdating($callback, $priority = 0)
  static function pivotUpdated($callback, $priority = 0)
  static function factory($count = null, $state = [])
  ```

### `class ChatMessage`
- **Namespace**: `\App\Models`
- **Hierarchy**: `ChatMessage extends Model implements UrlRoutable, Stringable, QueueableEntity, JsonSerializable, Jsonable, HasBroadcastChannel, CanBeEscapedWhenCastToString, ArrayAccess, Arrayable`
- **Table**: `tp_chat_messages`
- **Fillable**: `session_id`, `session_uuid`, `sender_type`, `sender_id`, `message`, `uuid`, `is_read`
- **Eloquent Relationships**:
  - `session()`: `belongsTo(ChatSession::class, 'session_id')`
  - `sender()`: `belongsTo(User::class, 'sender_id')`
- **Public API Methods**:
  ```php
  function session(): Illuminate\Database\Eloquent\Relations\BelongsTo
  function sender()
  function getSenderNameAttribute()
  function markAsRead()
  function scopeUnread($query)
  function scopeFromUsers($query)
  function scopeFromAdmin($query)
  static function factory($count = null, $state = [])
  ```

### `class ChatSession`
- **Namespace**: `\App\Models`
- **Hierarchy**: `ChatSession extends Model implements UrlRoutable, Stringable, QueueableEntity, JsonSerializable, Jsonable, HasBroadcastChannel, CanBeEscapedWhenCastToString, ArrayAccess, Arrayable`
- **Table**: `tp_chat_sessions`
- **Fillable**: `user_id`, `guest_name`, `guest_email`, `session_id`, `uuid`, `status`, `last_message_at`
- **Eloquent Relationships**:
  - `user()`: `belongsTo(User::class)`
  - `messages()`: `hasMany(ChatMessage::class, 'session_id')`
  - `latestMessage()`: `hasOne(ChatMessage::class, 'session_id')->latest()`
- **Public API Methods**:
  ```php
  function user(): Illuminate\Database\Eloquent\Relations\BelongsTo
  function messages(): Illuminate\Database\Eloquent\Relations\HasMany
  function latestMessage()
  function getUnreadMessagesCountAttribute()
  function getDisplayNameAttribute()
  function scopeActive($query)
  function scopeWithUnreadMessages($query)
  static function factory($count = null, $state = [])
  ```

### `class Comment`
- **Namespace**: `\App\Models`
- **Hierarchy**: `Comment extends Model implements UrlRoutable, Stringable, QueueableEntity, JsonSerializable, Jsonable, HasBroadcastChannel, CanBeEscapedWhenCastToString, ArrayAccess, Arrayable`
- **Table**: `tp_comments`
- **Fillable**: `uuid`, `name`, `email`, `content`, `id_post`, `type_post`, `rating`, `status`
- **Public API Methods**:
  ```php
  function scopeApproved($query)
  function scopePending($query)
  function scopeByType($query, $type)
  function scopeByItem($query, $type, $itemId)
  function scopeRecent($query, $days = 7)
  function getTypePostNameAttribute()
  static function getPostTypes()
  static function all($columns = []): Illuminate\Database\Eloquent\Collection
  static function destroy($ids): int
  function newEloquentBuilder($query): Illuminate\Database\Eloquent\Builder
  function getObservableEvents()
  function fireModelEvent($event, $halt = true, $relationName = null, $ids = [], $idsAttributes = [])
  static function factory($count = null, $state = [])
  function applyScopes(): Illuminate\Database\Eloquent\Builder|static
  function cache(array $tags = []): Illuminate\Contracts\Cache\Factory|Illuminate\Contracts\Cache\Repository
  function disableModelCaching(): static
  function flushCache(array $tags = []): void
  function isCachable(): bool
  function shouldFallbackToDatabase(): bool
  function isCacheConnectionException(Throwable $exception): bool
  function withCacheFallback(callable $operation, string $context, ?callable $fallback = null): mixed
  static function bootCachable(): void
  function newModelCachingEloquentBuilder($query): Illuminate\Database\Eloquent\Builder
  function scopeDisableCache(Illuminate\Database\Eloquent\Builder $query): Illuminate\Database\Eloquent\Builder
  function scopeWithCacheCooldownSeconds(Illuminate\Database\Eloquent\Builder $query, ?int $seconds = null): Illuminate\Database\Eloquent\Builder
  static function pivotSyncing($callback, $priority = 0)
  static function pivotSynced($callback, $priority = 0)
  static function pivotAttaching($callback, $priority = 0)
  static function pivotAttached($callback, $priority = 0)
  static function pivotDetaching($callback, $priority = 0)
  static function pivotDetached($callback, $priority = 0)
  static function pivotUpdating($callback, $priority = 0)
  static function pivotUpdated($callback, $priority = 0)
  ```

### `class Contact`
- **Namespace**: `\App\Models`
- **Hierarchy**: `Contact extends Model implements UrlRoutable, Stringable, QueueableEntity, JsonSerializable, Jsonable, HasBroadcastChannel, CanBeEscapedWhenCastToString, ArrayAccess, Arrayable`
- **Table**: `tp_contacts`
- **Fillable**: `uuid`, `fullname`, `email`, `phone`, `subject`, `message`, `status`
- **Public API Methods**:
  ```php
  static function all($columns = []): Illuminate\Database\Eloquent\Collection
  static function destroy($ids): int
  function newEloquentBuilder($query): Illuminate\Database\Eloquent\Builder
  function getObservableEvents()
  function fireModelEvent($event, $halt = true, $relationName = null, $ids = [], $idsAttributes = [])
  function applyScopes(): Illuminate\Database\Eloquent\Builder|static
  function cache(array $tags = []): Illuminate\Contracts\Cache\Factory|Illuminate\Contracts\Cache\Repository
  function disableModelCaching(): static
  function flushCache(array $tags = []): void
  function isCachable(): bool
  function shouldFallbackToDatabase(): bool
  function isCacheConnectionException(Throwable $exception): bool
  function withCacheFallback(callable $operation, string $context, ?callable $fallback = null): mixed
  static function bootCachable(): void
  function newModelCachingEloquentBuilder($query): Illuminate\Database\Eloquent\Builder
  function scopeDisableCache(Illuminate\Database\Eloquent\Builder $query): Illuminate\Database\Eloquent\Builder
  function scopeWithCacheCooldownSeconds(Illuminate\Database\Eloquent\Builder $query, ?int $seconds = null): Illuminate\Database\Eloquent\Builder
  static function pivotSyncing($callback, $priority = 0)
  static function pivotSynced($callback, $priority = 0)
  static function pivotAttaching($callback, $priority = 0)
  static function pivotAttached($callback, $priority = 0)
  static function pivotDetaching($callback, $priority = 0)
  static function pivotDetached($callback, $priority = 0)
  static function pivotUpdating($callback, $priority = 0)
  static function pivotUpdated($callback, $priority = 0)
  static function factory($count = null, $state = [])
  ```

### `class Feature`
- **Namespace**: `\App\Models`
- **Hierarchy**: `Feature extends Model implements UrlRoutable, Stringable, QueueableEntity, JsonSerializable, Jsonable, HasBroadcastChannel, CanBeEscapedWhenCastToString, ArrayAccess, Arrayable`
- **Table**: `tp_features`
- **Fillable**: `title_vn`, `title_en`, `content_vn`, `content_en`, `image`, `stt`, `uuid`, `status`
- **Public API Methods**:
  ```php
  static function all($columns = []): Illuminate\Database\Eloquent\Collection
  static function destroy($ids): int
  function newEloquentBuilder($query): Illuminate\Database\Eloquent\Builder
  function getObservableEvents()
  function fireModelEvent($event, $halt = true, $relationName = null, $ids = [], $idsAttributes = [])
  function getImageVnAttribute($value)
  function getImageEnAttribute($value)
  function getImageAttribute($value = null)
  function getAvatarAttribute($value)
  function getLogoAttribute($value)
  function getFaviconAttribute($value)
  function getShareImageAttribute($value)
  function applyScopes(): Illuminate\Database\Eloquent\Builder|static
  function cache(array $tags = []): Illuminate\Contracts\Cache\Factory|Illuminate\Contracts\Cache\Repository
  function disableModelCaching(): static
  function flushCache(array $tags = []): void
  function isCachable(): bool
  function shouldFallbackToDatabase(): bool
  function isCacheConnectionException(Throwable $exception): bool
  function withCacheFallback(callable $operation, string $context, ?callable $fallback = null): mixed
  static function bootCachable(): void
  function newModelCachingEloquentBuilder($query): Illuminate\Database\Eloquent\Builder
  function scopeDisableCache(Illuminate\Database\Eloquent\Builder $query): Illuminate\Database\Eloquent\Builder
  function scopeWithCacheCooldownSeconds(Illuminate\Database\Eloquent\Builder $query, ?int $seconds = null): Illuminate\Database\Eloquent\Builder
  static function pivotSyncing($callback, $priority = 0)
  static function pivotSynced($callback, $priority = 0)
  static function pivotAttaching($callback, $priority = 0)
  static function pivotAttached($callback, $priority = 0)
  static function pivotDetaching($callback, $priority = 0)
  static function pivotDetached($callback, $priority = 0)
  static function pivotUpdating($callback, $priority = 0)
  static function pivotUpdated($callback, $priority = 0)
  static function factory($count = null, $state = [])
  ```

### `class FeedBack`
- **Namespace**: `\App\Models`
- **Hierarchy**: `FeedBack extends Model implements UrlRoutable, Stringable, QueueableEntity, JsonSerializable, Jsonable, HasBroadcastChannel, CanBeEscapedWhenCastToString, ArrayAccess, Arrayable`
- **Table**: `tp_feedback`
- **Fillable**: `uuid`, `name`, `message`, `stt`, `image`
- **Public API Methods**:
  ```php
  static function all($columns = []): Illuminate\Database\Eloquent\Collection
  static function destroy($ids): int
  function newEloquentBuilder($query): Illuminate\Database\Eloquent\Builder
  function getObservableEvents()
  function fireModelEvent($event, $halt = true, $relationName = null, $ids = [], $idsAttributes = [])
  function getImageVnAttribute($value)
  function getImageEnAttribute($value)
  function getImageAttribute($value = null)
  function getAvatarAttribute($value)
  function getLogoAttribute($value)
  function getFaviconAttribute($value)
  function getShareImageAttribute($value)
  function applyScopes(): Illuminate\Database\Eloquent\Builder|static
  function cache(array $tags = []): Illuminate\Contracts\Cache\Factory|Illuminate\Contracts\Cache\Repository
  function disableModelCaching(): static
  function flushCache(array $tags = []): void
  function isCachable(): bool
  function shouldFallbackToDatabase(): bool
  function isCacheConnectionException(Throwable $exception): bool
  function withCacheFallback(callable $operation, string $context, ?callable $fallback = null): mixed
  static function bootCachable(): void
  function newModelCachingEloquentBuilder($query): Illuminate\Database\Eloquent\Builder
  function scopeDisableCache(Illuminate\Database\Eloquent\Builder $query): Illuminate\Database\Eloquent\Builder
  function scopeWithCacheCooldownSeconds(Illuminate\Database\Eloquent\Builder $query, ?int $seconds = null): Illuminate\Database\Eloquent\Builder
  static function pivotSyncing($callback, $priority = 0)
  static function pivotSynced($callback, $priority = 0)
  static function pivotAttaching($callback, $priority = 0)
  static function pivotAttached($callback, $priority = 0)
  static function pivotDetaching($callback, $priority = 0)
  static function pivotDetached($callback, $priority = 0)
  static function pivotUpdating($callback, $priority = 0)
  static function pivotUpdated($callback, $priority = 0)
  static function factory($count = null, $state = [])
  ```

### `class Font`
- **Namespace**: `\App\Models`
- **Hierarchy**: `Font extends Model implements UrlRoutable, Stringable, QueueableEntity, JsonSerializable, Jsonable, HasBroadcastChannel, CanBeEscapedWhenCastToString, ArrayAccess, Arrayable`
- **Table**: `tp_fonts`
- **Fillable**: `name`, `family`, `file_path`, `type`, `css_url`, `is_active`, `sort_order`
- **Public API Methods**:
  ```php
  function getFullUrlAttribute()
  static function factory($count = null, $state = [])
  ```

### `class Gallery`
- **Namespace**: `\App\Models`
- **Hierarchy**: `Gallery extends Model implements UrlRoutable, Stringable, QueueableEntity, JsonSerializable, Jsonable, HasBroadcastChannel, CanBeEscapedWhenCastToString, ArrayAccess, Arrayable`
- **Table**: `tp_galleries`
- **Fillable**: `name_vn`, `status`, `image`, `stt`, `uuid`
- **Public API Methods**:
  ```php
  static function all($columns = []): Illuminate\Database\Eloquent\Collection
  static function destroy($ids): int
  function newEloquentBuilder($query): Illuminate\Database\Eloquent\Builder
  function getObservableEvents()
  function fireModelEvent($event, $halt = true, $relationName = null, $ids = [], $idsAttributes = [])
  function getImageVnAttribute($value)
  function getImageEnAttribute($value)
  function getImageAttribute($value = null)
  function getAvatarAttribute($value)
  function getLogoAttribute($value)
  function getFaviconAttribute($value)
  function getShareImageAttribute($value)
  function applyScopes(): Illuminate\Database\Eloquent\Builder|static
  function cache(array $tags = []): Illuminate\Contracts\Cache\Factory|Illuminate\Contracts\Cache\Repository
  function disableModelCaching(): static
  function flushCache(array $tags = []): void
  function isCachable(): bool
  function shouldFallbackToDatabase(): bool
  function isCacheConnectionException(Throwable $exception): bool
  function withCacheFallback(callable $operation, string $context, ?callable $fallback = null): mixed
  static function bootCachable(): void
  function newModelCachingEloquentBuilder($query): Illuminate\Database\Eloquent\Builder
  function scopeDisableCache(Illuminate\Database\Eloquent\Builder $query): Illuminate\Database\Eloquent\Builder
  function scopeWithCacheCooldownSeconds(Illuminate\Database\Eloquent\Builder $query, ?int $seconds = null): Illuminate\Database\Eloquent\Builder
  static function pivotSyncing($callback, $priority = 0)
  static function pivotSynced($callback, $priority = 0)
  static function pivotAttaching($callback, $priority = 0)
  static function pivotAttached($callback, $priority = 0)
  static function pivotDetaching($callback, $priority = 0)
  static function pivotDetached($callback, $priority = 0)
  static function pivotUpdating($callback, $priority = 0)
  static function pivotUpdated($callback, $priority = 0)
  static function factory($count = null, $state = [])
  ```

### `class MailConfig`
- **Namespace**: `\App\Models`
- **Hierarchy**: `MailConfig extends Model implements UrlRoutable, Stringable, QueueableEntity, JsonSerializable, Jsonable, HasBroadcastChannel, CanBeEscapedWhenCastToString, ArrayAccess, Arrayable`
- **Table**: `tp_mail_configs`
- **Fillable**: `mailer`, `host`, `port`, `username`, `password`, `encryption`, `from_address`, `from_name`, `is_active`, `additional_config`
- **Public API Methods**:
  ```php
  static function getActiveConfig()
  function toConfigArray()
  static function factory($count = null, $state = [])
  ```

### `class MailTemplate`
- **Namespace**: `\App\Models`
- **Hierarchy**: `MailTemplate extends Model implements UrlRoutable, Stringable, QueueableEntity, JsonSerializable, Jsonable, HasBroadcastChannel, CanBeEscapedWhenCastToString, ArrayAccess, Arrayable`
- **Table**: `tp_mail_templates`
- **Fillable**: `name`, `subject`, `content`, `variables`, `type`, `is_active`
- **Public API Methods**:
  ```php
  static function factory($count = null, $state = [])
  ```

### `class Media`
- **Namespace**: `\App\Models`
- **Hierarchy**: `Media extends Model implements UrlRoutable, Stringable, QueueableEntity, JsonSerializable, Jsonable, HasBroadcastChannel, CanBeEscapedWhenCastToString, ArrayAccess, Arrayable`
- **Table**: `tp_media`
- **Fillable**: `uuid`, `name`, `file_name`, `path`, `module`, `mime_type`, `extension`, `size`, `dimensions`, `created_by`
- **Eloquent Relationships**:
  - `user()`: `belongsTo(User::class, 'created_by')`
- **Public API Methods**:
  ```php
  function getUrlAttribute(): string
  function getFormattedSizeAttribute(): string
  function getIsImageAttribute(): bool
  function getIsVideoAttribute(): bool
  function user()
  static function factory($count = null, $state = [])
  ```

### `class Menu`
- **Namespace**: `\App\Models`
- **Hierarchy**: `Menu extends Model implements UrlRoutable, Stringable, QueueableEntity, JsonSerializable, Jsonable, HasBroadcastChannel, CanBeEscapedWhenCastToString, ArrayAccess, Arrayable`
- **Table**: `tp_menus`
- **Fillable**: `uuid`, `name_vn`, `name_en`, `link`, `slug`, `type`, `parent_id`, `object_id`, `stt`
- **Eloquent Relationships**:
  - `children()`: `hasMany(Menu::class, 'parent_id', 'id_menu') ->with('children') ->orderBy('stt', 'asc')`
  - `activeChildren()`: `hasMany(Menu::class, 'parent_id', 'id_menu') ->where('status', 1) ->with('activeChildren') ->orderBy('stt', 'asc')`
- **Public API Methods**:
  ```php
  function children()
  function activeChildren()
  static function all($columns = []): Illuminate\Database\Eloquent\Collection
  static function destroy($ids): int
  function newEloquentBuilder($query): Illuminate\Database\Eloquent\Builder
  function getObservableEvents()
  function fireModelEvent($event, $halt = true, $relationName = null, $ids = [], $idsAttributes = [])
  function applyScopes(): Illuminate\Database\Eloquent\Builder|static
  function cache(array $tags = []): Illuminate\Contracts\Cache\Factory|Illuminate\Contracts\Cache\Repository
  function disableModelCaching(): static
  function flushCache(array $tags = []): void
  function isCachable(): bool
  function shouldFallbackToDatabase(): bool
  function isCacheConnectionException(Throwable $exception): bool
  function withCacheFallback(callable $operation, string $context, ?callable $fallback = null): mixed
  static function bootCachable(): void
  function newModelCachingEloquentBuilder($query): Illuminate\Database\Eloquent\Builder
  function scopeDisableCache(Illuminate\Database\Eloquent\Builder $query): Illuminate\Database\Eloquent\Builder
  function scopeWithCacheCooldownSeconds(Illuminate\Database\Eloquent\Builder $query, ?int $seconds = null): Illuminate\Database\Eloquent\Builder
  static function pivotSyncing($callback, $priority = 0)
  static function pivotSynced($callback, $priority = 0)
  static function pivotAttaching($callback, $priority = 0)
  static function pivotAttached($callback, $priority = 0)
  static function pivotDetaching($callback, $priority = 0)
  static function pivotDetached($callback, $priority = 0)
  static function pivotUpdating($callback, $priority = 0)
  static function pivotUpdated($callback, $priority = 0)
  static function factory($count = null, $state = [])
  ```

### `class News`
- **Namespace**: `\App\Models`
- **Hierarchy**: `News extends Model implements UrlRoutable, Stringable, QueueableEntity, JsonSerializable, Jsonable, HasBroadcastChannel, CanBeEscapedWhenCastToString, ArrayAccess, Arrayable`
- **Table**: `tp_news`
- **Fillable**: `uuid`, `name_vn`, `name_en`, `intro_vn`, `intro_en`, `content_vn`, `content_en`, `category_id`, `stt`, `slug_vn`, `slug_en`, `image_vn`, `image_en`, `keyword_vn`, `keyword_en`, `description_vn`, `description_en`, `status`, `home`
- **Eloquent Relationships**:
  - `cate()`: `belongsTo(CateNew::class, 'category_id', 'id_cate_new')`
  - `activities()`: `morphMany(ActivitylogServiceProvider::determineActivityModel(), 'subject')`
- **Public API Methods**:
  ```php
  function getSlugAttribute()
  function getNameAttribute()
  function getImageAttribute()
  function getKeywordAttribute()
  function getKeywordsAttribute()
  function getDescriptionAttribute()
  function cate()
  static function all($columns = []): Illuminate\Database\Eloquent\Collection
  static function destroy($ids): int
  function newEloquentBuilder($query): Illuminate\Database\Eloquent\Builder
  function getObservableEvents()
  function fireModelEvent($event, $halt = true, $relationName = null, $ids = [], $idsAttributes = [])
  function getImageVnAttribute($value)
  function getImageEnAttribute($value)
  function getAvatarAttribute($value)
  function getLogoAttribute($value)
  function getFaviconAttribute($value)
  function getShareImageAttribute($value)
  function getActivitylogOptions(): Spatie\Activitylog\LogOptions
  static function addLogChange(Spatie\Activitylog\Contracts\LoggablePipe $pipe): void
  function isLogEmpty(array $changes): bool
  function disableLogging(): self
  function enableLogging(): self
  function activities(): Illuminate\Database\Eloquent\Relations\MorphMany
  function getDescriptionForEvent(string $eventName): string
  function getLogNameToUse(): ?string
  function attributesToBeLogged(): array
  function shouldLogUnguarded(): bool
  function attributeValuesToBeLogged(string $processingEvent): array
  static function logChanges(Illuminate\Database\Eloquent\Model $model): array
  function applyScopes(): Illuminate\Database\Eloquent\Builder|static
  function cache(array $tags = []): Illuminate\Contracts\Cache\Factory|Illuminate\Contracts\Cache\Repository
  function disableModelCaching(): static
  function flushCache(array $tags = []): void
  function isCachable(): bool
  function shouldFallbackToDatabase(): bool
  function isCacheConnectionException(Throwable $exception): bool
  function withCacheFallback(callable $operation, string $context, ?callable $fallback = null): mixed
  static function bootCachable(): void
  function newModelCachingEloquentBuilder($query): Illuminate\Database\Eloquent\Builder
  function scopeDisableCache(Illuminate\Database\Eloquent\Builder $query): Illuminate\Database\Eloquent\Builder
  function scopeWithCacheCooldownSeconds(Illuminate\Database\Eloquent\Builder $query, ?int $seconds = null): Illuminate\Database\Eloquent\Builder
  static function pivotSyncing($callback, $priority = 0)
  static function pivotSynced($callback, $priority = 0)
  static function pivotAttaching($callback, $priority = 0)
  static function pivotAttached($callback, $priority = 0)
  static function pivotDetaching($callback, $priority = 0)
  static function pivotDetached($callback, $priority = 0)
  static function pivotUpdating($callback, $priority = 0)
  static function pivotUpdated($callback, $priority = 0)
  static function factory($count = null, $state = [])
  ```

### `class NewsSetting`
- **Namespace**: `\App\Models`
- **Hierarchy**: `NewsSetting extends Model implements UrlRoutable, Stringable, QueueableEntity, JsonSerializable, Jsonable, HasBroadcastChannel, CanBeEscapedWhenCastToString, ArrayAccess, Arrayable`
- **Table**: `newssettings`
- **Fillable**: `uuid`, `key`, `value`, `type`, `group`, `description`, `is_active`, `sort_order`
- **Public API Methods**:
  ```php
  function getRouteKeyName()
  static function get($key, $default = null)
  static function set($key, $value, $type = 'text', $group = 'general', $description = null)
  function scopeByGroup($query, $group)
  function scopeActive($query)
  static function all($columns = []): Illuminate\Database\Eloquent\Collection
  static function destroy($ids): int
  function newEloquentBuilder($query): Illuminate\Database\Eloquent\Builder
  function getObservableEvents()
  function fireModelEvent($event, $halt = true, $relationName = null, $ids = [], $idsAttributes = [])
  function applyScopes(): Illuminate\Database\Eloquent\Builder|static
  function cache(array $tags = []): Illuminate\Contracts\Cache\Factory|Illuminate\Contracts\Cache\Repository
  function disableModelCaching(): static
  function flushCache(array $tags = []): void
  function isCachable(): bool
  function shouldFallbackToDatabase(): bool
  function isCacheConnectionException(Throwable $exception): bool
  function withCacheFallback(callable $operation, string $context, ?callable $fallback = null): mixed
  static function bootCachable(): void
  function newModelCachingEloquentBuilder($query): Illuminate\Database\Eloquent\Builder
  function scopeDisableCache(Illuminate\Database\Eloquent\Builder $query): Illuminate\Database\Eloquent\Builder
  function scopeWithCacheCooldownSeconds(Illuminate\Database\Eloquent\Builder $query, ?int $seconds = null): Illuminate\Database\Eloquent\Builder
  static function pivotSyncing($callback, $priority = 0)
  static function pivotSynced($callback, $priority = 0)
  static function pivotAttaching($callback, $priority = 0)
  static function pivotAttached($callback, $priority = 0)
  static function pivotDetaching($callback, $priority = 0)
  static function pivotDetached($callback, $priority = 0)
  static function pivotUpdating($callback, $priority = 0)
  static function pivotUpdated($callback, $priority = 0)
  static function factory($count = null, $state = [])
  ```

### `class OrderProduct`
- **Namespace**: `\App\Models`
- **Hierarchy**: `OrderProduct extends Model implements UrlRoutable, Stringable, QueueableEntity, JsonSerializable, Jsonable, HasBroadcastChannel, CanBeEscapedWhenCastToString, ArrayAccess, Arrayable`
- **Table**: `tp_order_product`
- **Fillable**: `uuid_order_product`, `order_status_id`, `product_id`, `quantity`, `price`, `attribute`
- **Eloquent Relationships**:
  - `product()`: `belongsTo(Product::class, 'product_id', 'id_product')`
  - `orderStatus()`: `belongsTo(OrderStatus::class, 'order_status_id', 'id_order_status')`
  - `activities()`: `morphMany(ActivitylogServiceProvider::determineActivityModel(), 'subject')`
- **Public API Methods**:
  ```php
  function product()
  function orderStatus()
  static function all($columns = []): Illuminate\Database\Eloquent\Collection
  static function destroy($ids): int
  function newEloquentBuilder($query): Illuminate\Database\Eloquent\Builder
  function getObservableEvents()
  function fireModelEvent($event, $halt = true, $relationName = null, $ids = [], $idsAttributes = [])
  function getActivitylogOptions(): Spatie\Activitylog\LogOptions
  static function addLogChange(Spatie\Activitylog\Contracts\LoggablePipe $pipe): void
  function isLogEmpty(array $changes): bool
  function disableLogging(): self
  function enableLogging(): self
  function activities(): Illuminate\Database\Eloquent\Relations\MorphMany
  function getDescriptionForEvent(string $eventName): string
  function getLogNameToUse(): ?string
  function attributesToBeLogged(): array
  function shouldLogUnguarded(): bool
  function attributeValuesToBeLogged(string $processingEvent): array
  static function logChanges(Illuminate\Database\Eloquent\Model $model): array
  function applyScopes(): Illuminate\Database\Eloquent\Builder|static
  function cache(array $tags = []): Illuminate\Contracts\Cache\Factory|Illuminate\Contracts\Cache\Repository
  function disableModelCaching(): static
  function flushCache(array $tags = []): void
  function isCachable(): bool
  function shouldFallbackToDatabase(): bool
  function isCacheConnectionException(Throwable $exception): bool
  function withCacheFallback(callable $operation, string $context, ?callable $fallback = null): mixed
  static function bootCachable(): void
  function newModelCachingEloquentBuilder($query): Illuminate\Database\Eloquent\Builder
  function scopeDisableCache(Illuminate\Database\Eloquent\Builder $query): Illuminate\Database\Eloquent\Builder
  function scopeWithCacheCooldownSeconds(Illuminate\Database\Eloquent\Builder $query, ?int $seconds = null): Illuminate\Database\Eloquent\Builder
  static function pivotSyncing($callback, $priority = 0)
  static function pivotSynced($callback, $priority = 0)
  static function pivotAttaching($callback, $priority = 0)
  static function pivotAttached($callback, $priority = 0)
  static function pivotDetaching($callback, $priority = 0)
  static function pivotDetached($callback, $priority = 0)
  static function pivotUpdating($callback, $priority = 0)
  static function pivotUpdated($callback, $priority = 0)
  static function factory($count = null, $state = [])
  ```

### `class OrderShipping`
- **Namespace**: `\App\Models`
- **Hierarchy**: `OrderShipping extends Model implements UrlRoutable, Stringable, QueueableEntity, JsonSerializable, Jsonable, HasBroadcastChannel, CanBeEscapedWhenCastToString, ArrayAccess, Arrayable`
- **Table**: `tp_order_shipping`
- **Fillable**: `uuid_order_shipping`, `f_name_order`, `l_name_order`, `phone`, `email`, `address`, `note`
- **Public API Methods**:
  ```php
  static function all($columns = []): Illuminate\Database\Eloquent\Collection
  static function destroy($ids): int
  function newEloquentBuilder($query): Illuminate\Database\Eloquent\Builder
  function getObservableEvents()
  function fireModelEvent($event, $halt = true, $relationName = null, $ids = [], $idsAttributes = [])
  function applyScopes(): Illuminate\Database\Eloquent\Builder|static
  function cache(array $tags = []): Illuminate\Contracts\Cache\Factory|Illuminate\Contracts\Cache\Repository
  function disableModelCaching(): static
  function flushCache(array $tags = []): void
  function isCachable(): bool
  function shouldFallbackToDatabase(): bool
  function isCacheConnectionException(Throwable $exception): bool
  function withCacheFallback(callable $operation, string $context, ?callable $fallback = null): mixed
  static function bootCachable(): void
  function newModelCachingEloquentBuilder($query): Illuminate\Database\Eloquent\Builder
  function scopeDisableCache(Illuminate\Database\Eloquent\Builder $query): Illuminate\Database\Eloquent\Builder
  function scopeWithCacheCooldownSeconds(Illuminate\Database\Eloquent\Builder $query, ?int $seconds = null): Illuminate\Database\Eloquent\Builder
  static function pivotSyncing($callback, $priority = 0)
  static function pivotSynced($callback, $priority = 0)
  static function pivotAttaching($callback, $priority = 0)
  static function pivotAttached($callback, $priority = 0)
  static function pivotDetaching($callback, $priority = 0)
  static function pivotDetached($callback, $priority = 0)
  static function pivotUpdating($callback, $priority = 0)
  static function pivotUpdated($callback, $priority = 0)
  static function factory($count = null, $state = [])
  ```

### `class OrderStatus`
- **Namespace**: `\App\Models`
- **Hierarchy**: `OrderStatus extends Model implements UrlRoutable, Stringable, QueueableEntity, JsonSerializable, Jsonable, HasBroadcastChannel, CanBeEscapedWhenCastToString, ArrayAccess, Arrayable`
- **Table**: `tp_order_status`
- **Fillable**: `uuid_order_status`, `shipping_id`, `payment_method`, `total`, `status`
- **Eloquent Relationships**:
  - `shipping()`: `belongsTo(OrderShipping::class, 'shipping_id', 'id_order_shipping')`
  - `orderProducts()`: `hasMany(OrderProduct::class, 'order_status_id', 'id_order_status')`
  - `activities()`: `morphMany(ActivitylogServiceProvider::determineActivityModel(), 'subject')`
- **Public API Methods**:
  ```php
  function scopePending($query)
  function scopeCompleted($query)
  function scopeCancelled($query)
  function scopeOverduePending($query, int $hours = 24)
  function shipping()
  function orderProducts()
  static function all($columns = []): Illuminate\Database\Eloquent\Collection
  static function destroy($ids): int
  function newEloquentBuilder($query): Illuminate\Database\Eloquent\Builder
  function getObservableEvents()
  function fireModelEvent($event, $halt = true, $relationName = null, $ids = [], $idsAttributes = [])
  function getActivitylogOptions(): Spatie\Activitylog\LogOptions
  static function addLogChange(Spatie\Activitylog\Contracts\LoggablePipe $pipe): void
  function isLogEmpty(array $changes): bool
  function disableLogging(): self
  function enableLogging(): self
  function activities(): Illuminate\Database\Eloquent\Relations\MorphMany
  function getDescriptionForEvent(string $eventName): string
  function getLogNameToUse(): ?string
  function attributesToBeLogged(): array
  function shouldLogUnguarded(): bool
  function attributeValuesToBeLogged(string $processingEvent): array
  static function logChanges(Illuminate\Database\Eloquent\Model $model): array
  function applyScopes(): Illuminate\Database\Eloquent\Builder|static
  function cache(array $tags = []): Illuminate\Contracts\Cache\Factory|Illuminate\Contracts\Cache\Repository
  function disableModelCaching(): static
  function flushCache(array $tags = []): void
  function isCachable(): bool
  function shouldFallbackToDatabase(): bool
  function isCacheConnectionException(Throwable $exception): bool
  function withCacheFallback(callable $operation, string $context, ?callable $fallback = null): mixed
  static function bootCachable(): void
  function newModelCachingEloquentBuilder($query): Illuminate\Database\Eloquent\Builder
  function scopeDisableCache(Illuminate\Database\Eloquent\Builder $query): Illuminate\Database\Eloquent\Builder
  function scopeWithCacheCooldownSeconds(Illuminate\Database\Eloquent\Builder $query, ?int $seconds = null): Illuminate\Database\Eloquent\Builder
  static function pivotSyncing($callback, $priority = 0)
  static function pivotSynced($callback, $priority = 0)
  static function pivotAttaching($callback, $priority = 0)
  static function pivotAttached($callback, $priority = 0)
  static function pivotDetaching($callback, $priority = 0)
  static function pivotDetached($callback, $priority = 0)
  static function pivotUpdating($callback, $priority = 0)
  static function pivotUpdated($callback, $priority = 0)
  static function factory($count = null, $state = [])
  ```

### `class Page`
- **Namespace**: `\App\Models`
- **Hierarchy**: `Page extends Model implements UrlRoutable, Stringable, QueueableEntity, JsonSerializable, Jsonable, HasBroadcastChannel, CanBeEscapedWhenCastToString, ArrayAccess, Arrayable`
- **Table**: `tp_pages`
- **Fillable**: `uuid`, `name_vn`, `name_en`, `slug_vn`, `slug_en`, `content_vn`, `content_en`, `footer`, `stt`, `image_vn`, `image_en`, `keyword_vn`, `keyword_en`, `description_vn`, `description_en`, `parent_id`
- **Eloquent Relationships**:
  - `activities()`: `morphMany(ActivitylogServiceProvider::determineActivityModel(), 'subject')`
- **Public API Methods**:
  ```php
  function getSlugAttribute()
  function getNameAttribute()
  function getImageAttribute()
  function getKeywordAttribute()
  function getKeywordsAttribute()
  function getDescriptionAttribute()
  static function all($columns = []): Illuminate\Database\Eloquent\Collection
  static function destroy($ids): int
  function newEloquentBuilder($query): Illuminate\Database\Eloquent\Builder
  function getObservableEvents()
  function fireModelEvent($event, $halt = true, $relationName = null, $ids = [], $idsAttributes = [])
  function getImageVnAttribute($value)
  function getImageEnAttribute($value)
  function getAvatarAttribute($value)
  function getLogoAttribute($value)
  function getFaviconAttribute($value)
  function getShareImageAttribute($value)
  function getActivitylogOptions(): Spatie\Activitylog\LogOptions
  static function addLogChange(Spatie\Activitylog\Contracts\LoggablePipe $pipe): void
  function isLogEmpty(array $changes): bool
  function disableLogging(): self
  function enableLogging(): self
  function activities(): Illuminate\Database\Eloquent\Relations\MorphMany
  function getDescriptionForEvent(string $eventName): string
  function getLogNameToUse(): ?string
  function attributesToBeLogged(): array
  function shouldLogUnguarded(): bool
  function attributeValuesToBeLogged(string $processingEvent): array
  static function logChanges(Illuminate\Database\Eloquent\Model $model): array
  function applyScopes(): Illuminate\Database\Eloquent\Builder|static
  function cache(array $tags = []): Illuminate\Contracts\Cache\Factory|Illuminate\Contracts\Cache\Repository
  function disableModelCaching(): static
  function flushCache(array $tags = []): void
  function isCachable(): bool
  function shouldFallbackToDatabase(): bool
  function isCacheConnectionException(Throwable $exception): bool
  function withCacheFallback(callable $operation, string $context, ?callable $fallback = null): mixed
  static function bootCachable(): void
  function newModelCachingEloquentBuilder($query): Illuminate\Database\Eloquent\Builder
  function scopeDisableCache(Illuminate\Database\Eloquent\Builder $query): Illuminate\Database\Eloquent\Builder
  function scopeWithCacheCooldownSeconds(Illuminate\Database\Eloquent\Builder $query, ?int $seconds = null): Illuminate\Database\Eloquent\Builder
  static function pivotSyncing($callback, $priority = 0)
  static function pivotSynced($callback, $priority = 0)
  static function pivotAttaching($callback, $priority = 0)
  static function pivotAttached($callback, $priority = 0)
  static function pivotDetaching($callback, $priority = 0)
  static function pivotDetached($callback, $priority = 0)
  static function pivotUpdating($callback, $priority = 0)
  static function pivotUpdated($callback, $priority = 0)
  static function factory($count = null, $state = [])
  ```

### `class Permission`
- **Namespace**: `\App\Models`
- **Hierarchy**: `Permission extends Model implements UrlRoutable, Stringable, QueueableEntity, JsonSerializable, Jsonable, HasBroadcastChannel, CanBeEscapedWhenCastToString, ArrayAccess, Arrayable`
- **Table**: `tp_permissions`
- **Fillable**: `name`, `display_name`, `group_name`, `description`
- **Eloquent Relationships**:
  - `roles()`: `belongsToMany(Role::class, 'tp_role_permission', 'permission_id', 'role_id')`
- **Public API Methods**:
  ```php
  function roles(): Illuminate\Database\Eloquent\Relations\BelongsToMany
  static function getByGroup()
  function scopeOfGroup($query, $groupName)
  static function all($columns = []): Illuminate\Database\Eloquent\Collection
  static function destroy($ids): int
  function newEloquentBuilder($query): Illuminate\Database\Eloquent\Builder
  function getObservableEvents()
  function fireModelEvent($event, $halt = true, $relationName = null, $ids = [], $idsAttributes = [])
  function applyScopes(): Illuminate\Database\Eloquent\Builder|static
  function cache(array $tags = []): Illuminate\Contracts\Cache\Factory|Illuminate\Contracts\Cache\Repository
  function disableModelCaching(): static
  function flushCache(array $tags = []): void
  function isCachable(): bool
  function shouldFallbackToDatabase(): bool
  function isCacheConnectionException(Throwable $exception): bool
  function withCacheFallback(callable $operation, string $context, ?callable $fallback = null): mixed
  static function bootCachable(): void
  function newModelCachingEloquentBuilder($query): Illuminate\Database\Eloquent\Builder
  function scopeDisableCache(Illuminate\Database\Eloquent\Builder $query): Illuminate\Database\Eloquent\Builder
  function scopeWithCacheCooldownSeconds(Illuminate\Database\Eloquent\Builder $query, ?int $seconds = null): Illuminate\Database\Eloquent\Builder
  static function pivotSyncing($callback, $priority = 0)
  static function pivotSynced($callback, $priority = 0)
  static function pivotAttaching($callback, $priority = 0)
  static function pivotAttached($callback, $priority = 0)
  static function pivotDetaching($callback, $priority = 0)
  static function pivotDetached($callback, $priority = 0)
  static function pivotUpdating($callback, $priority = 0)
  static function pivotUpdated($callback, $priority = 0)
  static function factory($count = null, $state = [])
  ```

### `class Product`
- **Namespace**: `\App\Models`
- **Hierarchy**: `Product extends Model implements UrlRoutable, Stringable, QueueableEntity, JsonSerializable, Jsonable, HasBroadcastChannel, CanBeEscapedWhenCastToString, ArrayAccess, Arrayable`
- **Table**: `tp_products`
- **Fillable**: `uuid`, `name_vn`, `name_en`, `slug_vn`, `slug_en`, `intro_vn`, `intro_en`, `price`, `price_old`, `content_vn`, `content_en`, `image_vn`, `image_en`, `image_detail`, `video`, `stt`, `keyword_vn`, `keyword_en`, `description_vn`, `description_en`, `category_id`, `brand_id`, `quantity`, `status`, `hot`, `home`, `sale`
- **Eloquent Relationships**:
  - `cate()`: `belongsTo(CateProduct::class, 'category_id', 'id_cate_product')`
  - `brand()`: `belongsTo(Brand::class, 'brand_id', 'id_brand')`
  - `activities()`: `morphMany(ActivitylogServiceProvider::determineActivityModel(), 'subject')`
- **Public API Methods**:
  ```php
  function getSlugAttribute()
  function getNameAttribute()
  function getImageAttribute()
  function getVideoUrlAttribute()
  function getKeywordAttribute()
  function getKeywordsAttribute()
  function getDescriptionAttribute()
  function scopeLowStock($query, int $threshold = 5)
  function scopeOutOfStock($query)
  function cate()
  function brand()
  static function all($columns = []): Illuminate\Database\Eloquent\Collection
  static function destroy($ids): int
  function newEloquentBuilder($query): Illuminate\Database\Eloquent\Builder
  function getObservableEvents()
  function fireModelEvent($event, $halt = true, $relationName = null, $ids = [], $idsAttributes = [])
  function getImageVnAttribute($value)
  function getImageEnAttribute($value)
  function getAvatarAttribute($value)
  function getLogoAttribute($value)
  function getFaviconAttribute($value)
  function getShareImageAttribute($value)
  function getActivitylogOptions(): Spatie\Activitylog\LogOptions
  static function addLogChange(Spatie\Activitylog\Contracts\LoggablePipe $pipe): void
  function isLogEmpty(array $changes): bool
  function disableLogging(): self
  function enableLogging(): self
  function activities(): Illuminate\Database\Eloquent\Relations\MorphMany
  function getDescriptionForEvent(string $eventName): string
  function getLogNameToUse(): ?string
  function attributesToBeLogged(): array
  function shouldLogUnguarded(): bool
  function attributeValuesToBeLogged(string $processingEvent): array
  static function logChanges(Illuminate\Database\Eloquent\Model $model): array
  function applyScopes(): Illuminate\Database\Eloquent\Builder|static
  function cache(array $tags = []): Illuminate\Contracts\Cache\Factory|Illuminate\Contracts\Cache\Repository
  function disableModelCaching(): static
  function flushCache(array $tags = []): void
  function isCachable(): bool
  function shouldFallbackToDatabase(): bool
  function isCacheConnectionException(Throwable $exception): bool
  function withCacheFallback(callable $operation, string $context, ?callable $fallback = null): mixed
  static function bootCachable(): void
  function newModelCachingEloquentBuilder($query): Illuminate\Database\Eloquent\Builder
  function scopeDisableCache(Illuminate\Database\Eloquent\Builder $query): Illuminate\Database\Eloquent\Builder
  function scopeWithCacheCooldownSeconds(Illuminate\Database\Eloquent\Builder $query, ?int $seconds = null): Illuminate\Database\Eloquent\Builder
  static function pivotSyncing($callback, $priority = 0)
  static function pivotSynced($callback, $priority = 0)
  static function pivotAttaching($callback, $priority = 0)
  static function pivotAttached($callback, $priority = 0)
  static function pivotDetaching($callback, $priority = 0)
  static function pivotDetached($callback, $priority = 0)
  static function pivotUpdating($callback, $priority = 0)
  static function pivotUpdated($callback, $priority = 0)
  static function factory($count = null, $state = [])
  ```

### `class ProductSetting`
- **Namespace**: `\App\Models`
- **Hierarchy**: `ProductSetting extends Model implements UrlRoutable, Stringable, QueueableEntity, JsonSerializable, Jsonable, HasBroadcastChannel, CanBeEscapedWhenCastToString, ArrayAccess, Arrayable`
- **Table**: `productsettings`
- **Fillable**: `uuid`, `key`, `value`, `type`, `group`, `description`, `is_active`, `currency_unit`, `sort_order`
- **Public API Methods**:
  ```php
  function getRouteKeyName()
  static function get($key, $default = null)
  static function getCurrencyUnit(): string
  static function set($key, $value, $type = 'text', $group = 'general', $description = null)
  function scopeByGroup($query, $group)
  function scopeActive($query)
  static function all($columns = []): Illuminate\Database\Eloquent\Collection
  static function destroy($ids): int
  function newEloquentBuilder($query): Illuminate\Database\Eloquent\Builder
  function getObservableEvents()
  function fireModelEvent($event, $halt = true, $relationName = null, $ids = [], $idsAttributes = [])
  function applyScopes(): Illuminate\Database\Eloquent\Builder|static
  function cache(array $tags = []): Illuminate\Contracts\Cache\Factory|Illuminate\Contracts\Cache\Repository
  function disableModelCaching(): static
  function flushCache(array $tags = []): void
  function isCachable(): bool
  function shouldFallbackToDatabase(): bool
  function isCacheConnectionException(Throwable $exception): bool
  function withCacheFallback(callable $operation, string $context, ?callable $fallback = null): mixed
  static function bootCachable(): void
  function newModelCachingEloquentBuilder($query): Illuminate\Database\Eloquent\Builder
  function scopeDisableCache(Illuminate\Database\Eloquent\Builder $query): Illuminate\Database\Eloquent\Builder
  function scopeWithCacheCooldownSeconds(Illuminate\Database\Eloquent\Builder $query, ?int $seconds = null): Illuminate\Database\Eloquent\Builder
  static function pivotSyncing($callback, $priority = 0)
  static function pivotSynced($callback, $priority = 0)
  static function pivotAttaching($callback, $priority = 0)
  static function pivotAttached($callback, $priority = 0)
  static function pivotDetaching($callback, $priority = 0)
  static function pivotDetached($callback, $priority = 0)
  static function pivotUpdating($callback, $priority = 0)
  static function pivotUpdated($callback, $priority = 0)
  static function factory($count = null, $state = [])
  ```

### `class Project`
- **Namespace**: `\App\Models`
- **Hierarchy**: `Project extends Model implements UrlRoutable, Stringable, QueueableEntity, JsonSerializable, Jsonable, HasBroadcastChannel, CanBeEscapedWhenCastToString, ArrayAccess, Arrayable`
- **Table**: `tp_projects`
- **Fillable**: `uuid`, `name_vn`, `name_en`, `intro_vn`, `intro_en`, `content_vn`, `content_en`, `category_id`, `stt`, `slug_vn`, `slug_en`, `image_vn`, `image_en`, `keyword_vn`, `keyword_en`, `description_vn`, `description_en`, `status`, `home`
- **Eloquent Relationships**:
  - `cate()`: `belongsTo(CateProject::class, 'category_id', 'id_cate_project')`
  - `activities()`: `morphMany(ActivitylogServiceProvider::determineActivityModel(), 'subject')`
- **Public API Methods**:
  ```php
  function getSlugAttribute()
  function getNameAttribute()
  function getImageAttribute()
  function getKeywordAttribute()
  function getKeywordsAttribute()
  function getDescriptionAttribute()
  function cate()
  static function all($columns = []): Illuminate\Database\Eloquent\Collection
  static function destroy($ids): int
  function newEloquentBuilder($query): Illuminate\Database\Eloquent\Builder
  function getObservableEvents()
  function fireModelEvent($event, $halt = true, $relationName = null, $ids = [], $idsAttributes = [])
  function getImageVnAttribute($value)
  function getImageEnAttribute($value)
  function getAvatarAttribute($value)
  function getLogoAttribute($value)
  function getFaviconAttribute($value)
  function getShareImageAttribute($value)
  function getActivitylogOptions(): Spatie\Activitylog\LogOptions
  static function addLogChange(Spatie\Activitylog\Contracts\LoggablePipe $pipe): void
  function isLogEmpty(array $changes): bool
  function disableLogging(): self
  function enableLogging(): self
  function activities(): Illuminate\Database\Eloquent\Relations\MorphMany
  function getDescriptionForEvent(string $eventName): string
  function getLogNameToUse(): ?string
  function attributesToBeLogged(): array
  function shouldLogUnguarded(): bool
  function attributeValuesToBeLogged(string $processingEvent): array
  static function logChanges(Illuminate\Database\Eloquent\Model $model): array
  function applyScopes(): Illuminate\Database\Eloquent\Builder|static
  function cache(array $tags = []): Illuminate\Contracts\Cache\Factory|Illuminate\Contracts\Cache\Repository
  function disableModelCaching(): static
  function flushCache(array $tags = []): void
  function isCachable(): bool
  function shouldFallbackToDatabase(): bool
  function isCacheConnectionException(Throwable $exception): bool
  function withCacheFallback(callable $operation, string $context, ?callable $fallback = null): mixed
  static function bootCachable(): void
  function newModelCachingEloquentBuilder($query): Illuminate\Database\Eloquent\Builder
  function scopeDisableCache(Illuminate\Database\Eloquent\Builder $query): Illuminate\Database\Eloquent\Builder
  function scopeWithCacheCooldownSeconds(Illuminate\Database\Eloquent\Builder $query, ?int $seconds = null): Illuminate\Database\Eloquent\Builder
  static function pivotSyncing($callback, $priority = 0)
  static function pivotSynced($callback, $priority = 0)
  static function pivotAttaching($callback, $priority = 0)
  static function pivotAttached($callback, $priority = 0)
  static function pivotDetaching($callback, $priority = 0)
  static function pivotDetached($callback, $priority = 0)
  static function pivotUpdating($callback, $priority = 0)
  static function pivotUpdated($callback, $priority = 0)
  static function factory($count = null, $state = [])
  ```

### `class Role`
- **Namespace**: `\App\Models`
- **Hierarchy**: `Role extends Model implements UrlRoutable, Stringable, QueueableEntity, JsonSerializable, Jsonable, HasBroadcastChannel, CanBeEscapedWhenCastToString, ArrayAccess, Arrayable`
- **Table**: `tp_roles`
- **Fillable**: `name`, `display_name`, `description`
- **Eloquent Relationships**:
  - `permissions()`: `belongsToMany(Permission::class, 'tp_role_permission', 'role_id', 'permission_id')`
  - `users()`: `belongsToMany(User::class, 'tp_role_user', 'role_id', 'user_id')`
  - `activities()`: `morphMany(ActivitylogServiceProvider::determineActivityModel(), 'subject')`
- **Public API Methods**:
  ```php
  function permissions(): Illuminate\Database\Eloquent\Relations\BelongsToMany
  function users(): Illuminate\Database\Eloquent\Relations\BelongsToMany
  function givePermissionTo($permissions): void
  function revokePermissionTo($permissions): void
  function syncPermissions($permissions): void
  function hasPermission($permissionName): bool
  static function all($columns = []): Illuminate\Database\Eloquent\Collection
  static function destroy($ids): int
  function newEloquentBuilder($query): Illuminate\Database\Eloquent\Builder
  function getObservableEvents()
  function fireModelEvent($event, $halt = true, $relationName = null, $ids = [], $idsAttributes = [])
  function getActivitylogOptions(): Spatie\Activitylog\LogOptions
  static function addLogChange(Spatie\Activitylog\Contracts\LoggablePipe $pipe): void
  function isLogEmpty(array $changes): bool
  function disableLogging(): self
  function enableLogging(): self
  function activities(): Illuminate\Database\Eloquent\Relations\MorphMany
  function getDescriptionForEvent(string $eventName): string
  function getLogNameToUse(): ?string
  function attributesToBeLogged(): array
  function shouldLogUnguarded(): bool
  function attributeValuesToBeLogged(string $processingEvent): array
  static function logChanges(Illuminate\Database\Eloquent\Model $model): array
  function applyScopes(): Illuminate\Database\Eloquent\Builder|static
  function cache(array $tags = []): Illuminate\Contracts\Cache\Factory|Illuminate\Contracts\Cache\Repository
  function disableModelCaching(): static
  function flushCache(array $tags = []): void
  function isCachable(): bool
  function shouldFallbackToDatabase(): bool
  function isCacheConnectionException(Throwable $exception): bool
  function withCacheFallback(callable $operation, string $context, ?callable $fallback = null): mixed
  static function bootCachable(): void
  function newModelCachingEloquentBuilder($query): Illuminate\Database\Eloquent\Builder
  function scopeDisableCache(Illuminate\Database\Eloquent\Builder $query): Illuminate\Database\Eloquent\Builder
  function scopeWithCacheCooldownSeconds(Illuminate\Database\Eloquent\Builder $query, ?int $seconds = null): Illuminate\Database\Eloquent\Builder
  static function pivotSyncing($callback, $priority = 0)
  static function pivotSynced($callback, $priority = 0)
  static function pivotAttaching($callback, $priority = 0)
  static function pivotAttached($callback, $priority = 0)
  static function pivotDetaching($callback, $priority = 0)
  static function pivotDetached($callback, $priority = 0)
  static function pivotUpdating($callback, $priority = 0)
  static function pivotUpdated($callback, $priority = 0)
  static function factory($count = null, $state = [])
  ```

### `class Service`
- **Namespace**: `\App\Models`
- **Hierarchy**: `Service extends Model implements UrlRoutable, Stringable, QueueableEntity, JsonSerializable, Jsonable, HasBroadcastChannel, CanBeEscapedWhenCastToString, ArrayAccess, Arrayable`
- **Table**: `tp_services`
- **Fillable**: `uuid`, `name_vn`, `name_en`, `intro_vn`, `intro_en`, `content_vn`, `content_en`, `category_id`, `stt`, `slug_vn`, `slug_en`, `image_vn`, `image_en`, `keyword_vn`, `keyword_en`, `description_vn`, `description_en`, `status`, `home`
- **Eloquent Relationships**:
  - `cate()`: `belongsTo(CateService::class, 'category_id', 'id_cate_service')`
  - `activities()`: `morphMany(ActivitylogServiceProvider::determineActivityModel(), 'subject')`
- **Public API Methods**:
  ```php
  function getSlugAttribute()
  function getNameAttribute()
  function getImageAttribute()
  function getKeywordAttribute()
  function getKeywordsAttribute()
  function getDescriptionAttribute()
  function cate()
  static function all($columns = []): Illuminate\Database\Eloquent\Collection
  static function destroy($ids): int
  function newEloquentBuilder($query): Illuminate\Database\Eloquent\Builder
  function getObservableEvents()
  function fireModelEvent($event, $halt = true, $relationName = null, $ids = [], $idsAttributes = [])
  function getImageVnAttribute($value)
  function getImageEnAttribute($value)
  function getAvatarAttribute($value)
  function getLogoAttribute($value)
  function getFaviconAttribute($value)
  function getShareImageAttribute($value)
  function getActivitylogOptions(): Spatie\Activitylog\LogOptions
  static function addLogChange(Spatie\Activitylog\Contracts\LoggablePipe $pipe): void
  function isLogEmpty(array $changes): bool
  function disableLogging(): self
  function enableLogging(): self
  function activities(): Illuminate\Database\Eloquent\Relations\MorphMany
  function getDescriptionForEvent(string $eventName): string
  function getLogNameToUse(): ?string
  function attributesToBeLogged(): array
  function shouldLogUnguarded(): bool
  function attributeValuesToBeLogged(string $processingEvent): array
  static function logChanges(Illuminate\Database\Eloquent\Model $model): array
  function applyScopes(): Illuminate\Database\Eloquent\Builder|static
  function cache(array $tags = []): Illuminate\Contracts\Cache\Factory|Illuminate\Contracts\Cache\Repository
  function disableModelCaching(): static
  function flushCache(array $tags = []): void
  function isCachable(): bool
  function shouldFallbackToDatabase(): bool
  function isCacheConnectionException(Throwable $exception): bool
  function withCacheFallback(callable $operation, string $context, ?callable $fallback = null): mixed
  static function bootCachable(): void
  function newModelCachingEloquentBuilder($query): Illuminate\Database\Eloquent\Builder
  function scopeDisableCache(Illuminate\Database\Eloquent\Builder $query): Illuminate\Database\Eloquent\Builder
  function scopeWithCacheCooldownSeconds(Illuminate\Database\Eloquent\Builder $query, ?int $seconds = null): Illuminate\Database\Eloquent\Builder
  static function pivotSyncing($callback, $priority = 0)
  static function pivotSynced($callback, $priority = 0)
  static function pivotAttaching($callback, $priority = 0)
  static function pivotAttached($callback, $priority = 0)
  static function pivotDetaching($callback, $priority = 0)
  static function pivotDetached($callback, $priority = 0)
  static function pivotUpdating($callback, $priority = 0)
  static function pivotUpdated($callback, $priority = 0)
  static function factory($count = null, $state = [])
  ```

### `class SiteSetting`
- **Namespace**: `\App\Models`
- **Hierarchy**: `SiteSetting extends Model implements UrlRoutable, Stringable, QueueableEntity, JsonSerializable, Jsonable, HasBroadcastChannel, CanBeEscapedWhenCastToString, ArrayAccess, Arrayable`
- **Table**: `sitesettings`
- **Fillable**: `key`, `type`, `value`, `group`, `description`
- **Public API Methods**:
  ```php
  static function factory($count = null, $state = [])
  ```

### `class Slider`
- **Namespace**: `\App\Models`
- **Hierarchy**: `Slider extends Model implements UrlRoutable, Stringable, QueueableEntity, JsonSerializable, Jsonable, HasBroadcastChannel, CanBeEscapedWhenCastToString, ArrayAccess, Arrayable`
- **Table**: `tp_sliders`
- **Fillable**: `name_vn`, `name_en`, `link`, `image_desktop_vn`, `image_desktop_en`, `image_mobile_vn`, `image_mobile_en`, `stt`, `uuid`, `status`
- **Eloquent Relationships**:
  - `activities()`: `morphMany(ActivitylogServiceProvider::determineActivityModel(), 'subject')`
- **Public API Methods**:
  ```php
  function getImageDesktopVnAttribute($value)
  function getImageDesktopEnAttribute($value)
  function getImageMobileVnAttribute($value)
  function getImageMobileEnAttribute($value)
  function getImageDesktopAttribute($value = null)
  function getImageMobileAttribute($value = null)
  function getImageVnAttribute($value = null)
  function getImageEnAttribute($value = null)
  function getImageAttribute($value = null)
  static function all($columns = []): Illuminate\Database\Eloquent\Collection
  static function destroy($ids): int
  function newEloquentBuilder($query): Illuminate\Database\Eloquent\Builder
  function getObservableEvents()
  function fireModelEvent($event, $halt = true, $relationName = null, $ids = [], $idsAttributes = [])
  function getAvatarAttribute($value)
  function getLogoAttribute($value)
  function getFaviconAttribute($value)
  function getShareImageAttribute($value)
  function getActivitylogOptions(): Spatie\Activitylog\LogOptions
  static function addLogChange(Spatie\Activitylog\Contracts\LoggablePipe $pipe): void
  function isLogEmpty(array $changes): bool
  function disableLogging(): self
  function enableLogging(): self
  function activities(): Illuminate\Database\Eloquent\Relations\MorphMany
  function getDescriptionForEvent(string $eventName): string
  function getLogNameToUse(): ?string
  function attributesToBeLogged(): array
  function shouldLogUnguarded(): bool
  function attributeValuesToBeLogged(string $processingEvent): array
  static function logChanges(Illuminate\Database\Eloquent\Model $model): array
  function applyScopes(): Illuminate\Database\Eloquent\Builder|static
  function cache(array $tags = []): Illuminate\Contracts\Cache\Factory|Illuminate\Contracts\Cache\Repository
  function disableModelCaching(): static
  function flushCache(array $tags = []): void
  function isCachable(): bool
  function shouldFallbackToDatabase(): bool
  function isCacheConnectionException(Throwable $exception): bool
  function withCacheFallback(callable $operation, string $context, ?callable $fallback = null): mixed
  static function bootCachable(): void
  function newModelCachingEloquentBuilder($query): Illuminate\Database\Eloquent\Builder
  function scopeDisableCache(Illuminate\Database\Eloquent\Builder $query): Illuminate\Database\Eloquent\Builder
  function scopeWithCacheCooldownSeconds(Illuminate\Database\Eloquent\Builder $query, ?int $seconds = null): Illuminate\Database\Eloquent\Builder
  static function pivotSyncing($callback, $priority = 0)
  static function pivotSynced($callback, $priority = 0)
  static function pivotAttaching($callback, $priority = 0)
  static function pivotAttached($callback, $priority = 0)
  static function pivotDetaching($callback, $priority = 0)
  static function pivotDetached($callback, $priority = 0)
  static function pivotUpdating($callback, $priority = 0)
  static function pivotUpdated($callback, $priority = 0)
  static function factory($count = null, $state = [])
  ```

### `class System`
- **Namespace**: `\App\Models`
- **Hierarchy**: `System extends Model implements UrlRoutable, Stringable, QueueableEntity, JsonSerializable, Jsonable, HasBroadcastChannel, CanBeEscapedWhenCastToString, ArrayAccess, Arrayable`
- **Table**: `tp_systems`
- **Fillable**: `active_languages`, `email`, `email_alert`, `address_vn`, `address_en`, `phone`, `footer_vn`, `footer_en`, `facebook`, `youtube`, `twitter`, `instagram`, `zalo`, `favicon`, `logo`, `share_image`, `meta_title`, `name_vn`, `name_en`, `description_vn`, `description_en`, `keyword_vn`, `keyword_en`, `header_js`, `body_js`, `footer_js`, `map`, `contact_title_vn`, `contact_desc_vn`, `contact_title_en`, `contact_desc_en`
- **Eloquent Relationships**:
  - `activities()`: `morphMany(ActivitylogServiceProvider::determineActivityModel(), 'subject')`
- **Public API Methods**:
  ```php
  function getMetaNameAttribute()
  function getMetaKeywordAttribute()
  function getMetaDescriptionAttribute()
  static function all($columns = []): Illuminate\Database\Eloquent\Collection
  static function destroy($ids): int
  function newEloquentBuilder($query): Illuminate\Database\Eloquent\Builder
  function getObservableEvents()
  function fireModelEvent($event, $halt = true, $relationName = null, $ids = [], $idsAttributes = [])
  function getImageVnAttribute($value)
  function getImageEnAttribute($value)
  function getImageAttribute($value = null)
  function getAvatarAttribute($value)
  function getLogoAttribute($value)
  function getFaviconAttribute($value)
  function getShareImageAttribute($value)
  function getActivitylogOptions(): Spatie\Activitylog\LogOptions
  static function addLogChange(Spatie\Activitylog\Contracts\LoggablePipe $pipe): void
  function isLogEmpty(array $changes): bool
  function disableLogging(): self
  function enableLogging(): self
  function activities(): Illuminate\Database\Eloquent\Relations\MorphMany
  function getDescriptionForEvent(string $eventName): string
  function getLogNameToUse(): ?string
  function attributesToBeLogged(): array
  function shouldLogUnguarded(): bool
  function attributeValuesToBeLogged(string $processingEvent): array
  static function logChanges(Illuminate\Database\Eloquent\Model $model): array
  function applyScopes(): Illuminate\Database\Eloquent\Builder|static
  function cache(array $tags = []): Illuminate\Contracts\Cache\Factory|Illuminate\Contracts\Cache\Repository
  function disableModelCaching(): static
  function flushCache(array $tags = []): void
  function isCachable(): bool
  function shouldFallbackToDatabase(): bool
  function isCacheConnectionException(Throwable $exception): bool
  function withCacheFallback(callable $operation, string $context, ?callable $fallback = null): mixed
  static function bootCachable(): void
  function newModelCachingEloquentBuilder($query): Illuminate\Database\Eloquent\Builder
  function scopeDisableCache(Illuminate\Database\Eloquent\Builder $query): Illuminate\Database\Eloquent\Builder
  function scopeWithCacheCooldownSeconds(Illuminate\Database\Eloquent\Builder $query, ?int $seconds = null): Illuminate\Database\Eloquent\Builder
  static function pivotSyncing($callback, $priority = 0)
  static function pivotSynced($callback, $priority = 0)
  static function pivotAttaching($callback, $priority = 0)
  static function pivotAttached($callback, $priority = 0)
  static function pivotDetaching($callback, $priority = 0)
  static function pivotDetached($callback, $priority = 0)
  static function pivotUpdating($callback, $priority = 0)
  static function pivotUpdated($callback, $priority = 0)
  static function factory($count = null, $state = [])
  ```

### `class User`
- **Namespace**: `\App\Models`
- **Hierarchy**: `User extends User implements CanResetPassword, Authorizable, Authenticatable, UrlRoutable, Stringable, QueueableEntity, JsonSerializable, Jsonable, HasBroadcastChannel, CanBeEscapedWhenCastToString, ArrayAccess, Arrayable`
- **Table**: `users`
- **Fillable**: `uuid`, `fullname`, `username`, `phone`, `address`, `email`, `password`, `level`, `avatar`, `status`, `email_verified_at`
- **Eloquent Relationships**:
  - `tokens()`: `morphMany(Sanctum::$personalAccessTokenModel, 'tokenable')`
  - `activities()`: `morphMany(ActivitylogServiceProvider::determineActivityModel(), 'subject')`
  - `notifications()`: `morphMany(DatabaseNotification::class, 'notifiable')->latest()`
  - `roles()`: `belongsToMany(Role::class, 'tp_role_user', 'user_id', 'role_id')`
  - `directPermissions()`: `belongsToMany(Permission::class, 'tp_user_permission', 'user_id', 'permission_id')`
- **Public API Methods**:
  ```php
  static function all($columns = []): Illuminate\Database\Eloquent\Collection
  static function destroy($ids): int
  function newEloquentBuilder($query): Illuminate\Database\Eloquent\Builder
  function getObservableEvents()
  function fireModelEvent($event, $halt = true, $relationName = null, $ids = [], $idsAttributes = [])
  function tokens()
  function tokenCan(string $ability)
  function tokenCant(string $ability)
  function createToken(string $name, array $abilities = [], ?DateTimeInterface $expiresAt = null)
  function generateTokenString()
  function currentAccessToken()
  function withAccessToken($accessToken)
  function getImageVnAttribute($value)
  function getImageEnAttribute($value)
  function getImageAttribute($value = null)
  function getAvatarAttribute($value)
  function getLogoAttribute($value)
  function getFaviconAttribute($value)
  function getShareImageAttribute($value)
  function getActivitylogOptions(): Spatie\Activitylog\LogOptions
  static function addLogChange(Spatie\Activitylog\Contracts\LoggablePipe $pipe): void
  function isLogEmpty(array $changes): bool
  function disableLogging(): self
  function enableLogging(): self
  function activities(): Illuminate\Database\Eloquent\Relations\MorphMany
  function getDescriptionForEvent(string $eventName): string
  function getLogNameToUse(): ?string
  function attributesToBeLogged(): array
  function shouldLogUnguarded(): bool
  function attributeValuesToBeLogged(string $processingEvent): array
  static function logChanges(Illuminate\Database\Eloquent\Model $model): array
  function applyScopes(): Illuminate\Database\Eloquent\Builder|static
  function cache(array $tags = []): Illuminate\Contracts\Cache\Factory|Illuminate\Contracts\Cache\Repository
  function disableModelCaching(): static
  function flushCache(array $tags = []): void
  function isCachable(): bool
  function shouldFallbackToDatabase(): bool
  function isCacheConnectionException(Throwable $exception): bool
  function withCacheFallback(callable $operation, string $context, ?callable $fallback = null): mixed
  static function bootCachable(): void
  function newModelCachingEloquentBuilder($query): Illuminate\Database\Eloquent\Builder
  function scopeDisableCache(Illuminate\Database\Eloquent\Builder $query): Illuminate\Database\Eloquent\Builder
  function scopeWithCacheCooldownSeconds(Illuminate\Database\Eloquent\Builder $query, ?int $seconds = null): Illuminate\Database\Eloquent\Builder
  static function pivotSyncing($callback, $priority = 0)
  static function pivotSynced($callback, $priority = 0)
  static function pivotAttaching($callback, $priority = 0)
  static function pivotAttached($callback, $priority = 0)
  static function pivotDetaching($callback, $priority = 0)
  static function pivotDetached($callback, $priority = 0)
  static function pivotUpdating($callback, $priority = 0)
  static function pivotUpdated($callback, $priority = 0)
  static function factory($count = null, $state = [])
  function notifications()
  function readNotifications()
  function unreadNotifications()
  function notify($instance)
  function notifyNow($instance, ?array $channels = null)
  function routeNotificationFor($driver, $notification = null)
  function roles(): Illuminate\Database\Eloquent\Relations\BelongsToMany
  function directPermissions(): Illuminate\Database\Eloquent\Relations\BelongsToMany
  function isSuperAdmin(): bool
  function permissions()
  function getAllPermissions()
  function getPermissionsAttribute()
  function assignRole($roles): void
  function givePermissionTo($permissions): void
  function syncDirectPermissions($permissions): void
  function removeRole($roles): void
  function syncRoles($roles): void
  function hasRole($roles): bool
  function hasAllRoles($roles): bool
  function hasPermission($permission): bool
  function hasAllPermissions($permissions): bool
  function hasAnyPermission($permissions): bool
  function forgetCachedPermissions(): void
  function getPermissionsAttributeAttribute()
  function getUserPermissions()
  ```

---

## Repositories (Interfaces)

### `interface AboutRepositoryInterface`
- **Namespace**: `\App\Repositories\Interfaces`
- **Hierarchy**: `AboutRepositoryInterface implements RepositoryInterface`
- **Public API Methods**:
  ```php
  function getFilteredAbouts(array $filters, $perPage = 10)
  function getActiveAbouts()
  function getAboutBySlug($slug)
  ```

### `interface ActivityLogRepositoryInterface`
- **Namespace**: `\App\Repositories\Interfaces`
- **Hierarchy**: `ActivityLogRepositoryInterface implements RepositoryInterface`
- **Public API Methods**:
  ```php
  function getFilteredLogs(array $filters = [], int $perPage = 20): Illuminate\Pagination\LengthAwarePaginator
  function getDistinctLogNames(): array
  function findById(int $id): ?Spatie\Activitylog\Models\Activity
  function cleanOldLogs(int $days = 60): int
  function deleteLog(int $id): bool
  ```

### `interface BrandRepositoryInterface`
- **Namespace**: `\App\Repositories\Interfaces`
- **Hierarchy**: `BrandRepositoryInterface implements RepositoryInterface`
- **Public API Methods**:
  ```php
  function getFilteredBrands(array $filters, $perPage = 10)
  function getActiveBrands()
  function updateOrder($uuid, $order)
  ```

### `interface CateNewRepositoryInterface`
- **Namespace**: `\App\Repositories\Interfaces`
- **Hierarchy**: `CateNewRepositoryInterface implements RepositoryInterface`
- **Public API Methods**:
  ```php
  function getFilteredCategories(array $filters, $perPage = 10)
  function getActiveParentCategories()
  function getCategoriesWithChildren()
  ```

### `interface CateProductRepositoryInterface`
- **Namespace**: `\App\Repositories\Interfaces`
- **Hierarchy**: `CateProductRepositoryInterface implements RepositoryInterface`
- **Public API Methods**:
  ```php
  function getFilteredCategories(array $filters, $perPage = 10)
  function getActiveParentCategories()
  function getCategoriesWithChildren()
  ```

### `interface CateProjectRepositoryInterface`
- **Namespace**: `\App\Repositories\Interfaces`
- **Hierarchy**: `CateProjectRepositoryInterface implements RepositoryInterface`
- **Public API Methods**:
  ```php
  function getFilteredCategories(array $filters, $perPage = 10)
  function getActiveParentCategories()
  function getCategoriesWithChildren()
  ```

### `interface CateServiceRepositoryInterface`
- **Namespace**: `\App\Repositories\Interfaces`
- **Hierarchy**: `CateServiceRepositoryInterface implements RepositoryInterface`
- **Public API Methods**:
  ```php
  function getFilteredCategories(array $filters, $perPage = 10)
  function getActiveParentCategories()
  function getCategoriesWithChildren()
  ```

### `interface ChatRepositoryInterface`
- **Namespace**: `\App\Repositories\Interfaces`
- **Hierarchy**: `ChatRepositoryInterface implements RepositoryInterface`
- **Public API Methods**:
  ```php
  function getActiveSessions($perPage = 15)
  function getOrCreateSession(array $data)
  function sendMessage($sessionUuid, array $messageData)
  function markMessagesAsRead($sessionUuid, $senderType = 'user')
  function getUnreadMessagesCount()
  function closeSession($sessionUuid)
  function getSessionMessages($sessionUuid, $limit = 50)
  function getSessionsWithUnreadMessages()
  function findBySessionId($sessionId)
  ```

### `interface CommentRepositoryInterface`
- **Namespace**: `\App\Repositories\Interfaces`
- **Hierarchy**: `CommentRepositoryInterface implements RepositoryInterface`
- **Public API Methods**:
  ```php
  function getFilteredComments(array $filters, $perPage = 10)
  function getApprovedCommentsForItem($type, $itemId)
  function createComment(array $data)
  ```

### `interface ContactRepositoryInterface`
- **Namespace**: `\App\Repositories\Interfaces`
- **Hierarchy**: `ContactRepositoryInterface implements RepositoryInterface`
- **Public API Methods**:
  ```php
  function getFilteredContacts(array $filters, $perPage = 10)
  function getActiveContacts()
  ```

### `interface DashboardRepositoryInterface`
- **Namespace**: `\App\Repositories\Interfaces`
- **Public API Methods**:
  ```php
  function getRevenueAndOrderSummary(): array
  function getDailyRevenueChart(int $days = 30): array
  function getMonthlyRevenueChart(int $year): array
  function getTopSellingProducts(int $limit = 5): array
  function getLowStockProducts(int $threshold = 5, int $limit = 10): array
  function getOverduePendingOrders(int $hours = 24, int $limit = 10): array
  function getRecentOrders(int $limit = 5): array
  ```

### `interface FeatureRepositoryInterface`
- **Namespace**: `\App\Repositories\Interfaces`
- **Hierarchy**: `FeatureRepositoryInterface implements RepositoryInterface`
- **Public API Methods**:
  ```php
  function getFilteredFeatures(array $filters, $perPage = 10)
  function getActiveFeatures()
  function updateOrder($uuid, $order)
  ```

### `interface FeedbackRepositoryInterface`
- **Namespace**: `\App\Repositories\Interfaces`
- **Hierarchy**: `FeedbackRepositoryInterface implements RepositoryInterface`
- **Public API Methods**:
  ```php
  function getFilteredFeedback(array $filters, $perPage = 10)
  function getActiveFeedback()
  function updateStatus($uuid, $status)
  ```

### `interface GalleryRepositoryInterface`
- **Namespace**: `\App\Repositories\Interfaces`
- **Hierarchy**: `GalleryRepositoryInterface implements RepositoryInterface`
- **Public API Methods**:
  ```php
  function getFilteredGalleries(array $filters, $perPage = 10)
  function getActiveGalleries()
  function updateOrder($uuid, $order)
  ```

### `interface MailConfigRepositoryInterface`
- **Namespace**: `\App\Repositories\Interfaces`
- **Public API Methods**:
  ```php
  function getActiveConfig()
  function setActiveConfig($id)
  function getAllConfigs()
  function createConfig(array $data)
  function updateConfig(array $data, $id)
  function deleteConfig($id)
  function testConfig(array $config)
  function find($id, $columns = [])
  ```

### `interface MediaRepositoryInterface`
- **Namespace**: `\App\Repositories\Interfaces`
- **Hierarchy**: `MediaRepositoryInterface implements RepositoryInterface`
- **Public API Methods**:
  ```php
  function paginateWithFilters(array $filters = [], int $perPage = 24)
  function findByPath(string $path)
  ```

### `interface MenuRepositoryInterface`
- **Namespace**: `\App\Repositories\Interfaces`
- **Hierarchy**: `MenuRepositoryInterface implements RepositoryInterface`
- **Public API Methods**:
  ```php
  function getFilteredMenus(array $filters, $perPage = 10)
  function getActiveMenus()
  function getMenuTree()
  function getMaxStt(int $parentId): int
  function updateHierarchy(array $nodes, int $parentId = 0, int $depth = 1): void
  ```

### `interface NewsRepositoryInterface`
- **Namespace**: `\App\Repositories\Interfaces`
- **Hierarchy**: `NewsRepositoryInterface implements RepositoryInterface`
- **Public API Methods**:
  ```php
  function getFilteredNews(array $filters, $perPage = 10)
  function getActiveCategories()
  ```

### `interface OrderRepositoryInterface`
- **Namespace**: `\App\Repositories\Interfaces`
- **Hierarchy**: `OrderRepositoryInterface implements RepositoryInterface`
- **Public API Methods**:
  ```php
  function getOrdersPaginated(array $filters = [], int $perPage = 10)
  function findByUuid($uuid, $columns = [])
  function getOrderDetailByUuid(string $uuid)
  function getOrderProducts(int $orderStatusId)
  function updateStatusByUuid(string $uuid, $status, string $field = 'status')
  function deleteOrderByUuid(string $uuid)
  ```

### `interface PageRepositoryInterface`
- **Namespace**: `\App\Repositories\Interfaces`
- **Hierarchy**: `PageRepositoryInterface implements RepositoryInterface`
- **Public API Methods**:
  ```php
  function getFilteredPages(array $filters, $perPage = 10)
  function getActivePages()
  function getPageBySlug($slug)
  ```

### `interface ProductRepositoryInterface`
- **Namespace**: `\App\Repositories\Interfaces`
- **Hierarchy**: `ProductRepositoryInterface implements RepositoryInterface`
- **Public API Methods**:
  ```php
  function getFilteredProducts(array $filters, $perPage = 10)
  function getActiveCategories()
  function getActiveBrands()
  ```

### `interface ProjectRepositoryInterface`
- **Namespace**: `\App\Repositories\Interfaces`
- **Hierarchy**: `ProjectRepositoryInterface implements RepositoryInterface`
- **Public API Methods**:
  ```php
  function getFilteredProjects(array $filters, $perPage = 10)
  function getActiveCategories()
  ```

### `interface RepositoryInterface`
- **Namespace**: `\App\Repositories\Interfaces`
- **Public API Methods**:
  ```php
  function all($columns = [])
  function paginate($perPage = 10, $columns = [])
  function find($id, $columns = [])
  function findBy($field, $value, $columns = [])
  function findByUuid($uuid, $columns = [])
  function findByUuids(array $uuids, $columns = [])
  function create(array $data)
  function update(array $data, $id)
  function delete($id)
  function deleteMultiple(array $ids)
  function getFilteredPaginate(array $filters, $perPage = 10, $columns = [])
  function getModelInstance()
  function generateUniqueSlug(string $name, ?string $uuid = null, ?string $tableName = null, string $slugField = 'slug_vn', ?string $primaryKey = null)
  function createWithAutoSlug(array $data, string $nameField = 'name_vn', string $slugField = 'slug_vn')
  ```

### `interface ServiceRepositoryInterface`
- **Namespace**: `\App\Repositories\Interfaces`
- **Hierarchy**: `ServiceRepositoryInterface implements RepositoryInterface`
- **Public API Methods**:
  ```php
  function getFilteredServices(array $filters, $perPage = 10)
  function getActiveCategories()
  ```

### `interface SiteSettingRepositoryInterface`
- **Namespace**: `\App\Repositories\Interfaces`
- **Hierarchy**: `SiteSettingRepositoryInterface implements RepositoryInterface`
- **Public API Methods**:
  ```php
  function getByKey($key)
  function getByGroup($group)
  function updateOrCreate(array $attributes, array $values = [])
  ```

### `interface SliderRepositoryInterface`
- **Namespace**: `\App\Repositories\Interfaces`
- **Hierarchy**: `SliderRepositoryInterface implements RepositoryInterface`
- **Public API Methods**:
  ```php
  function getFilteredSliders(array $filters, $perPage = 10)
  function getActiveSliders()
  function getSlidersByType($type)
  function updateOrder($uuid, $order)
  ```

### `interface SystemRepositoryInterface`
- **Namespace**: `\App\Repositories\Interfaces`
- **Hierarchy**: `SystemRepositoryInterface implements RepositoryInterface`
- **Public API Methods**:
  ```php
  function getSetting($key)
  function updateSetting($key, $value)
  ```

### `interface UserRepositoryInterface`
- **Namespace**: `\App\Repositories\Interfaces`
- **Hierarchy**: `UserRepositoryInterface implements RepositoryInterface`
- **Public API Methods**:
  ```php
  function getFilteredUsers(array $filters, $perPage = 10)
  function createUser(array $data)
  function updateUser(array $data, $id)
  function getUserWithRolesByUuid($uuid)
  function getAllRoles()
  function deleteByUuids(array $uuids)
  ```

---

## Repositories (Eloquent)

### `class AboutRepository`
- **Namespace**: `\App\Repositories\Eloquent`
- **Hierarchy**: `AboutRepository extends BaseRepository implements RepositoryInterface, AboutRepositoryInterface`
- **Public API Methods**:
  ```php
  function getFilteredAbouts(array $filters, $perPage = 10)
  function getActiveAbouts()
  function getAboutBySlug($slug)
  ```

### `class ActivityLogRepository`
- **Namespace**: `\App\Repositories\Eloquent`
- **Hierarchy**: `ActivityLogRepository extends BaseRepository implements RepositoryInterface, ActivityLogRepositoryInterface`
- **Public API Methods**:
  ```php
  function getFilteredLogs(array $filters = [], int $perPage = 20): Illuminate\Pagination\LengthAwarePaginator
  function getDistinctLogNames(): array
  function findById(int $id): ?Spatie\Activitylog\Models\Activity
  function cleanOldLogs(int $days = 60): int
  function deleteLog(int $id): bool
  ```

### `abstract class BaseRepository`
- **Namespace**: `\App\Repositories\Eloquent`
- **Hierarchy**: `BaseRepository implements RepositoryInterface`
- **Public API Methods**:
  ```php
  function getModelInstance()
  function all($columns = [])
  function paginate($perPage = 10, $columns = [])
  function find($id, $columns = [])
  function findBy($field, $value, $columns = [])
  function findByUuid($uuid, $columns = [])
  function create(array $data)
  function update(array $data, $id)
  function delete($id)
  function deleteMultiple(array $ids)
  function getFilteredPaginate(array $filters, $perPage = 10, $columns = [])
  function updateStatus($uuid, $status)
  function updateOrder($uuid, $order)
  function deleteByUuids(array $uuids)
  function findByUuids(array $uuids, $columns = [])
  function generateUniqueSlug(string $name, ?string $uuid = null, ?string $tableName = null, string $slugField = 'slug_vn', ?string $primaryKey = null)
  function createWithAutoSlug(array $data, string $nameField = 'name_vn', string $slugField = 'slug_vn')
  ```

### `class BrandRepository`
- **Namespace**: `\App\Repositories\Eloquent`
- **Hierarchy**: `BrandRepository extends BaseRepository implements RepositoryInterface, BrandRepositoryInterface`
- **Public API Methods**:
  ```php
  function getFilteredBrands(array $filters, $perPage = 10)
  function getActiveBrands()
  function updateOrder($uuid, $order)
  ```

### `class CateNewRepository`
- **Namespace**: `\App\Repositories\Eloquent`
- **Hierarchy**: `CateNewRepository extends BaseRepository implements RepositoryInterface, CateNewRepositoryInterface`
- **Public API Methods**:
  ```php
  function getFilteredCategories(array $filters, $perPage = 10)
  function getActiveParentCategories()
  function getCategoriesWithChildren()
  ```

### `class CateProductRepository`
- **Namespace**: `\App\Repositories\Eloquent`
- **Hierarchy**: `CateProductRepository extends BaseRepository implements RepositoryInterface, CateProductRepositoryInterface`
- **Public API Methods**:
  ```php
  function getFilteredCategories(array $filters, $perPage = 10)
  function getActiveParentCategories()
  function getCategoriesWithChildren()
  ```

### `class CateProjectRepository`
- **Namespace**: `\App\Repositories\Eloquent`
- **Hierarchy**: `CateProjectRepository extends BaseRepository implements RepositoryInterface, CateProjectRepositoryInterface`
- **Public API Methods**:
  ```php
  function getFilteredCategories(array $filters, $perPage = 10)
  function getActiveParentCategories()
  function getCategoriesWithChildren()
  ```

### `class CateServiceRepository`
- **Namespace**: `\App\Repositories\Eloquent`
- **Hierarchy**: `CateServiceRepository extends BaseRepository implements RepositoryInterface, CateServiceRepositoryInterface`
- **Public API Methods**:
  ```php
  function getFilteredCategories(array $filters, $perPage = 10)
  function getActiveParentCategories()
  function getCategoriesWithChildren()
  ```

### `class ChatRepository`
- **Namespace**: `\App\Repositories\Eloquent`
- **Hierarchy**: `ChatRepository extends BaseRepository implements RepositoryInterface, ChatRepositoryInterface`
- **Public API Methods**:
  ```php
  function getActiveSessions($perPage = 15)
  function getOrCreateSession(array $data)
  function sendMessage($sessionUuid, array $messageData)
  function markMessagesAsRead($sessionUuid, $senderType = 'user')
  function getUnreadMessagesCount()
  function closeSession($sessionUuid)
  function getSessionMessages($sessionUuid, $limit = 50)
  function getSessionsWithUnreadMessages()
  function findBySessionId($sessionId)
  function createSession(array $data)
  ```

### `class CommentRepository`
- **Namespace**: `\App\Repositories\Eloquent`
- **Hierarchy**: `CommentRepository extends BaseRepository implements RepositoryInterface, CommentRepositoryInterface`
- **Public API Methods**:
  ```php
  function getFilteredComments(array $filters, $perPage = 10)
  function getApprovedCommentsForItem($type, $itemId)
  function createComment(array $data)
  ```

### `class ContactRepository`
- **Namespace**: `\App\Repositories\Eloquent`
- **Hierarchy**: `ContactRepository extends BaseRepository implements RepositoryInterface, ContactRepositoryInterface`
- **Public API Methods**:
  ```php
  function getFilteredContacts(array $filters, $perPage = 10)
  function getActiveContacts()
  ```

### `class DashboardRepository`
- **Namespace**: `\App\Repositories\Eloquent`
- **Hierarchy**: `DashboardRepository implements DashboardRepositoryInterface`
- **Public API Methods**:
  ```php
  function getRevenueAndOrderSummary(): array
  function getDailyRevenueChart(int $days = 30): array
  function getMonthlyRevenueChart(int $year): array
  function getTopSellingProducts(int $limit = 5): array
  function getLowStockProducts(int $threshold = 5, int $limit = 10): array
  function getOverduePendingOrders(int $hours = 24, int $limit = 10): array
  function getRecentOrders(int $limit = 5): array
  ```

### `class FeatureRepository`
- **Namespace**: `\App\Repositories\Eloquent`
- **Hierarchy**: `FeatureRepository extends BaseRepository implements RepositoryInterface, FeatureRepositoryInterface`
- **Public API Methods**:
  ```php
  function getFilteredFeatures(array $filters, $perPage = 10)
  function getActiveFeatures()
  function updateOrder($uuid, $order)
  ```

### `class FeedBackRepository`
- **Namespace**: `\App\Repositories\Eloquent`
- **Hierarchy**: `FeedBackRepository extends BaseRepository implements RepositoryInterface, FeedbackRepositoryInterface`
- **Public API Methods**:
  ```php
  function getFilteredFeedback(array $filters, $perPage = 10)
  function getActiveFeedback()
  function updateStatus($uuid, $status)
  ```

### `class GalleryRepository`
- **Namespace**: `\App\Repositories\Eloquent`
- **Hierarchy**: `GalleryRepository extends BaseRepository implements RepositoryInterface, GalleryRepositoryInterface`
- **Public API Methods**:
  ```php
  function getFilteredGalleries(array $filters, $perPage = 10)
  function getActiveGalleries()
  function updateOrder($uuid, $order)
  ```

### `class MailConfigRepository`
- **Namespace**: `\App\Repositories\Eloquent`
- **Hierarchy**: `MailConfigRepository extends BaseRepository implements RepositoryInterface, MailConfigRepositoryInterface`
- **Public API Methods**:
  ```php
  function getActiveConfig()
  function setActiveConfig($id)
  function getAllConfigs()
  function createConfig(array $data)
  function updateConfig(array $data, $id)
  function deleteConfig($id)
  function testConfig(array $config)
  function find($id, $columns = [])
  ```

### `class MediaRepository`
- **Namespace**: `\App\Repositories\Eloquent`
- **Hierarchy**: `MediaRepository extends BaseRepository implements RepositoryInterface, MediaRepositoryInterface`
- **Public API Methods**:
  ```php
  function paginateWithFilters(array $filters = [], int $perPage = 24)
  function findByPath(string $path)
  ```

### `class MenuRepository`
- **Namespace**: `\App\Repositories\Eloquent`
- **Hierarchy**: `MenuRepository extends BaseRepository implements RepositoryInterface, MenuRepositoryInterface`
- **Public API Methods**:
  ```php
  function getFilteredMenus(array $filters, $perPage = 10)
  function getActiveMenus()
  function getMenuTree()
  function getMaxStt(int $parentId): int
  function updateHierarchy(array $nodes, int $parentId = 0, int $depth = 1): void
  ```

### `class NewsRepository`
- **Namespace**: `\App\Repositories\Eloquent`
- **Hierarchy**: `NewsRepository extends BaseRepository implements RepositoryInterface, NewsRepositoryInterface`
- **Public API Methods**:
  ```php
  function getFilteredNews(array $filters, $perPage = 10)
  function getActiveCategories()
  ```

### `class OrderRepository`
- **Namespace**: `\App\Repositories\Eloquent`
- **Hierarchy**: `OrderRepository extends BaseRepository implements RepositoryInterface, OrderRepositoryInterface`
- **Public API Methods**:
  ```php
  function getOrdersPaginated(array $filters = [], int $perPage = 10)
  function findByUuid($uuid, $columns = [])
  function getOrderDetailByUuid(string $uuid)
  function getOrderProducts(int $orderStatusId)
  function updateStatusByUuid(string $uuid, $status, string $field = 'status')
  function deleteOrderByUuid(string $uuid)
  ```

### `class PageRepository`
- **Namespace**: `\App\Repositories\Eloquent`
- **Hierarchy**: `PageRepository extends BaseRepository implements RepositoryInterface, PageRepositoryInterface`
- **Public API Methods**:
  ```php
  function getFilteredPages(array $filters, $perPage = 10)
  function getActivePages()
  function getPageBySlug($slug)
  ```

### `class ProductRepository`
- **Namespace**: `\App\Repositories\Eloquent`
- **Hierarchy**: `ProductRepository extends BaseRepository implements RepositoryInterface, ProductRepositoryInterface`
- **Public API Methods**:
  ```php
  function getFilteredProducts(array $filters, $perPage = 10)
  function getActiveCategories()
  function getActiveBrands()
  ```

### `class ProjectRepository`
- **Namespace**: `\App\Repositories\Eloquent`
- **Hierarchy**: `ProjectRepository extends BaseRepository implements RepositoryInterface, ProjectRepositoryInterface`
- **Public API Methods**:
  ```php
  function getFilteredProjects(array $filters, $perPage = 10)
  function getActiveCategories()
  ```

### `class ServiceRepository`
- **Namespace**: `\App\Repositories\Eloquent`
- **Hierarchy**: `ServiceRepository extends BaseRepository implements RepositoryInterface, ServiceRepositoryInterface`
- **Public API Methods**:
  ```php
  function getFilteredServices(array $filters, $perPage = 10)
  function getActiveCategories()
  ```

### `class SiteSettingRepository`
- **Namespace**: `\App\Repositories\Eloquent`
- **Hierarchy**: `SiteSettingRepository extends BaseRepository implements RepositoryInterface, SiteSettingRepositoryInterface`
- **Public API Methods**:
  ```php
  function getByKey($key)
  function getByGroup($group)
  function updateOrCreate(array $attributes, array $values = [])
  ```

### `class SliderRepository`
- **Namespace**: `\App\Repositories\Eloquent`
- **Hierarchy**: `SliderRepository extends BaseRepository implements RepositoryInterface, SliderRepositoryInterface`
- **Public API Methods**:
  ```php
  function getFilteredSliders(array $filters, $perPage = 10)
  function getActiveSliders()
  function getSlidersByType($type)
  function updateOrder($uuid, $order)
  ```

### `class SystemRepository`
- **Namespace**: `\App\Repositories\Eloquent`
- **Hierarchy**: `SystemRepository extends BaseRepository implements RepositoryInterface, SystemRepositoryInterface`
- **Public API Methods**:
  ```php
  function getSetting($key)
  function updateSetting($key, $value)
  ```

### `class UserRepository`
- **Namespace**: `\App\Repositories\Eloquent`
- **Hierarchy**: `UserRepository extends BaseRepository implements RepositoryInterface, UserRepositoryInterface`
- **Public API Methods**:
  ```php
  function getFilteredUsers(array $filters, $perPage = 10)
  function createUser(array $data)
  function updateUser(array $data, $id)
  function getUserWithRolesByUuid($uuid)
  function getAllRoles()
  function deleteByUuids(array $uuids)
  ```

---

## Services

### `class ActivityLogService`
- **Namespace**: `\App\Services`
- **Public API Methods**:
  ```php
  function getLogs(array $filters = [], int $perPage = 20): Illuminate\Pagination\LengthAwarePaginator
  function getLogNames(): array
  function getLogDetail(int $id): ?array
  function formatDiff(Spatie\Activitylog\Models\Activity $log): array
  function getEventBadge(?string $event): array
  function cleanLogs(int $days = 60): int
  function deleteLog(int $id): bool
  ```

### `class CacheService`
- **Namespace**: `\App\Services`
- **Public API Methods**:
  ```php
  static function remember(string $tag, string $key, int $ttl, callable $callback)
  static function forgetTag(string $tag): bool
  static function forgetTags(array $tags): bool
  static function forget(string $tag, string $key): bool
  static function getTtl(string $type = 'medium'): int
  static function clearAll(): bool
  static function getTagSize(string $tag): int
  ```

### `class CartService`
- **Namespace**: `\App\Services`
- **Public API Methods**:
  ```php
  function getCart(): array
  function isEmpty(): bool
  function addProduct(Illuminate\Http\Request $request, string $uuid, int $quantity = 1): bool
  function updateCart(Illuminate\Http\Request $request): void
  function removeItem(string $uuid, int $stt): bool
  function clearCart(): void
  function getTotalItems(): int
  function getTotalPrice(): float
  ```

### `class CheckoutService`
- **Namespace**: `\App\Services`
- **Public API Methods**:
  ```php
  function canCheckout(): bool
  function processOrder(Illuminate\Http\Request $request): ?int
  function clearCartAfterOrder(): void
  function getOrderDetails(int $orderId): ?array
  ```

### `class CommentService`
- **Namespace**: `\App\Services`
- **Public API Methods**:
  ```php
  function getApprovedCommentsForItem($type, $itemId)
  function createComment(array $data)
  function validateAndCreateComment(Illuminate\Http\Request $request, $type, $itemId)
  ```

### `class DashboardService`
- **Namespace**: `\App\Services`
- **Public API Methods**:
  ```php
  function getOverviewData(bool $refresh = false): array
  function getRevenueChartData(string $period = '30_days', ?int $year = null): array
  function flushCache(?int $year = null): void
  ```

### `class DataRemovalService`
- **Namespace**: `\App\Services`
- **Public API Methods**:
  ```php
  function destroyAllByUUIDs($model, $uuids, $imageFolder)
  function destroyData($model, $uuid, $imageFolder)
  ```

### `class DemoImageDownloaderService`
- **Namespace**: `\App\Services`
- **Public API Methods**:
  ```php
  function cleanOldUploads(): bool
  function fetchAndSaveImage(string $keyword, int $width = 800, int $height = 600): ?string
  ```

### `class HomeService`
- **Namespace**: `\App\Services`
- **Public API Methods**:
  ```php
  function getHomeData()
  ```

### `class ImageService`
- **Namespace**: `\App\Services`
- **Public API Methods**:
  ```php
  function saveImage($request, string $imageFolder, string $fieldName, array $options = [])
  function updateImage($request, $model, string $imageFolder, string $fieldName, array $options = [])
  function deleteImage($fileName, string $imageFolder): bool
  function convertToWebp(Illuminate\Http\UploadedFile|string $file, string $targetPath, int $quality = 80, int $maxWidth = 1600): string|bool
  function handleDetailImages($request, $imageFolder, array $options = [])
  function updateDetailImages($request, $model, string $imageFolder, string $fieldName = 'image_detail', array $options = [])
  function saveImageAsWebp($request, string $imageFolder, string $fieldName, array $options = [])
  ```

### `class MailConfigService`
- **Namespace**: `\App\Services`
- **Public API Methods**:
  ```php
  function getActiveMailConfig()
  function getAllConfigs()
  function createConfig(array $data)
  function updateConfig(array $data, $id)
  function setActiveConfig($id)
  function deleteConfig($id)
  function testConfig(array $config)
  function getConfigById($id)
  function createMailer()
  ```

### `class MailTemplateService`
- **Namespace**: `\App\Services`
- **Public API Methods**:
  ```php
  function getAll()
  function getById($id)
  function getByType($type)
  function getActiveByType($type)
  function create(array $data)
  function update($id, array $data)
  function delete($id)
  function paginate($perPage = 15)
  function clearCache()
  ```

### `class MediaService`
- **Namespace**: `\App\Services`
- **Public API Methods**:
  ```php
  function paginate(array $filters = [], int $perPage = 24)
  function upload(Illuminate\Http\UploadedFile $file, string $module = 'general', ?int $userId = null): ?App\Models\Media
  function uploadMultiple(array $files, string $module = 'general', ?int $userId = null): array
  function delete(int $id): bool
  ```

### `class ModelToggleService`
- **Namespace**: `\App\Services`
- **Public API Methods**:
  ```php
  function toggleModelStatus($uuid, $status, $fieldName, $model)
  function updateModelOrder($request, $uuid, $model)
  ```

### `class NewsService`
- **Namespace**: `\App\Services`
- **Public API Methods**:
  ```php
  function getCategoryNewsData($id_cate_new)
  function getDetailNewsData($id_news)
  ```

### `class ProductService`
- **Namespace**: `\App\Services`
- **Public API Methods**:
  ```php
  function getCategoryProductData($id_cate_product, Illuminate\Http\Request $request)
  function getDetailProductData($id_product)
  function getBrandProductData($id_brand, ?Illuminate\Http\Request $request = null)
  ```

### `class ProjectService`
- **Namespace**: `\App\Services`
- **Public API Methods**:
  ```php
  function getCategoryProjectData($id_cate_project)
  function getDetailProjectData($id_project)
  ```

### `class RateLimitService`
- **Namespace**: `\App\Services`
- **Public API Methods**:
  ```php
  function isBlocked(string $key): bool
  function incrementAttempts(string $key): void
  function clearAttempts(string $key): void
  function getAttempts(string $key): int
  function getErrorMessage(string $key = ''): string
  function getRemainingAttempts(string $key): int
  function getRemainingTime(string $key): int
  static function forLogin(int $maxAttempts = 5, int $decayMinutes = 15): self
  static function forContact(int $maxAttempts = 3, int $decayMinutes = 5): self
  static function forCart(int $maxAttempts = 10, int $decayMinutes = 1): self
  static function forCheckout(int $maxAttempts = 3, int $decayMinutes = 10): self
  static function forContent(int $maxAttempts = 100, int $decayMinutes = 1): self
  ```

### `class SearchService`
- **Namespace**: `\App\Services`
- **Public API Methods**:
  ```php
  function searchProducts(Illuminate\Http\Request $request)
  function handleSearchRequest(Illuminate\Http\Request $request)
  function getSearchSuggestionsForAjax(Illuminate\Http\Request $request)
  ```

### `class ServiceService`
- **Namespace**: `\App\Services`
- **Public API Methods**:
  ```php
  function getCategoryServiceData($id_cate_service)
  function getDetailServiceData($id_service)
  ```

### `class SiteSettingService`
- **Namespace**: `\App\Services`
- **Public API Methods**:
  ```php
  function getValue($key, $default = null)
  function getByGroup($group)
  function setSetting($key, $value, $type = 'text', $group = null, $description = null)
  function getHomepageSettings()
  ```

### `class SlugResolutionService`
- **Namespace**: `\App\Services`
- **Public API Methods**:
  ```php
  function findContentBySlug(string $slug): ?array
  ```

### `class SlugService`
- **Namespace**: `\App\Services`
- **Public API Methods**:
  ```php
  function generateUniqueSlug(string $name, string $table, string $column = 'slug_vn', ?int $id = null): string
  function generateUniqueSlugWithId(string $name, int $id, string $table, string $column = 'slug_vn'): string
  function isSlugUniqueAcrossTables(string $slug, ?string $excludeTable = null, ?int $excludeId = null): bool
  function generateUniqueSlugGlobal(string $name, string $table, string $column = 'slug_vn', ?int $id = null): string
  function generateUniqueSlugWithIdGlobal(string $name, int $id, string $table, string $column = 'slug_vn'): string
  ```

---

## Controllers (Frontend)

### `class CartController`
- **Namespace**: `\App\Http\Controllers\Frontend`
- **Hierarchy**: `CartController extends Controller`
- **Public API Methods**:
  ```php
  function index()
  function addToCart(Illuminate\Http\Request $request, $uuid, $quantity = 1)
  function updateCart(Illuminate\Http\Request $request)
  function removeItem(Illuminate\Http\Request $request, $uuid, $stt)
  ```

### `class CheckoutController`
- **Namespace**: `\App\Http\Controllers\Frontend`
- **Hierarchy**: `CheckoutController extends Controller`
- **Public API Methods**:
  ```php
  function index()
  function checkoutStore(Illuminate\Http\Request $request)
  function orderSuccess(Illuminate\Http\Request $request, $orderId = null)
  ```

### `class ContactController`
- **Namespace**: `\App\Http\Controllers\Frontend`
- **Hierarchy**: `ContactController extends Controller`
- **Public API Methods**:
  ```php
  function contact()
  function postContact(App\Http\Requests\Frontend\ContactRequest $request)
  ```

### `class HomeController`
- **Namespace**: `\App\Http\Controllers\Frontend`
- **Hierarchy**: `HomeController extends Controller`
- **Public API Methods**:
  ```php
  function home()
  function factory()
  ```

### `class NewsController`
- **Namespace**: `\App\Http\Controllers\Frontend`
- **Hierarchy**: `NewsController extends Controller`
- **Public API Methods**:
  ```php
  function categoryNews($id_cate_new)
  function detailNews($id_news)
  ```

### `class PageController`
- **Namespace**: `\App\Http\Controllers\Frontend`
- **Hierarchy**: `PageController extends Controller`
- **Public API Methods**:
  ```php
  function page($id_page)
  ```

### `class ProductController`
- **Namespace**: `\App\Http\Controllers\Frontend`
- **Hierarchy**: `ProductController extends Controller`
- **Public API Methods**:
  ```php
  function categoryProduct($id_cate_product)
  function detailProduct($id_product)
  function brandProduct($id_brand)
  ```

### `class ProjectController`
- **Namespace**: `\App\Http\Controllers\Frontend`
- **Hierarchy**: `ProjectController extends Controller`
- **Public API Methods**:
  ```php
  function categoryProject($id_cate_project)
  function detailProject($id_project)
  ```

### `class RouteController`
- **Namespace**: `\App\Http\Controllers\Frontend`
- **Hierarchy**: `RouteController extends Controller`
- **Public API Methods**:
  ```php
  function resolve($slug)
  ```

### `class SearchController`
- **Namespace**: `\App\Http\Controllers\Frontend`
- **Hierarchy**: `SearchController extends Controller`
- **Public API Methods**:
  ```php
  function search(Illuminate\Http\Request $request)
  function suggestions(Illuminate\Http\Request $request)
  ```

### `class ServiceController`
- **Namespace**: `\App\Http\Controllers\Frontend`
- **Hierarchy**: `ServiceController extends Controller`
- **Public API Methods**:
  ```php
  function categoryService($id_cate_service)
  function detailService($id_service)
  ```

### `class SitemapController`
- **Namespace**: `\App\Http\Controllers\Frontend`
- **Hierarchy**: `SitemapController extends Controller`
- **Public API Methods**:
  ```php
  function index()
  ```

---

## Controllers (Admin)

### `class AboutController`
- **Namespace**: `\App\Http\Controllers\Admin`
- **Hierarchy**: `AboutController extends BaseController`
- **Public API Methods**:
  ```php
  function index(Illuminate\Http\Request $request)
  function edit($uuid, $currentPage = 1)
  function update(App\Http\Requests\Admin\AboutRequest $request, string $uuid)
  ```

### `class ActivityLogController`
- **Namespace**: `\App\Http\Controllers\Admin`
- **Hierarchy**: `ActivityLogController extends BaseController`
- **Public API Methods**:
  ```php
  function index(Illuminate\Http\Request $request): Illuminate\View\View
  function show(int $id): Illuminate\Http\JsonResponse
  function clean(Illuminate\Http\Request $request): Illuminate\Http\RedirectResponse
  function delete($id): Illuminate\Http\RedirectResponse
  ```

### `class AnalyticController`
- **Namespace**: `\App\Http\Controllers\Admin`
- **Hierarchy**: `AnalyticController extends BaseController`
- **Public API Methods**:
  ```php
  function index(Illuminate\Http\Request $request)
  ```

### `class BaseController`
- **Namespace**: `\App\Http\Controllers\Admin`
- **Hierarchy**: `BaseController extends Controller`
- **Public API Methods**:
  ```php
  function view_admin(string $page, array $data = [])
  function route_admin(string $page, array $params = [], array $flash = [], $pageParam = null)
  function resolveCreatedAt($inputDate, $oldDate = null)
  function destroy(string $uuid)
  function destroyAll(Illuminate\Http\Request $request)
  ```

### `class BrandController`
- **Namespace**: `\App\Http\Controllers\Admin`
- **Hierarchy**: `BrandController extends BaseController`
- **Public API Methods**:
  ```php
  function index(Illuminate\Http\Request $request)
  function store(App\Http\Requests\Admin\BrandRequest $request)
  function update(App\Http\Requests\Admin\BrandRequest $request, string $uuid)
  function edit($uuid, $currentPage = 1)
  function status($uuid, $status, $field)
  function numericalOrder(Illuminate\Http\Request $request, $uuid)
  function destroy(string $uuid)
  function destroyAll(Illuminate\Http\Request $request)
  function create()
  ```

### `class CateNewController`
- **Namespace**: `\App\Http\Controllers\Admin`
- **Hierarchy**: `CateNewController extends BaseController`
- **Public API Methods**:
  ```php
  function index(Illuminate\Http\Request $request)
  function create()
  function store(App\Http\Requests\Admin\CateNewRequest $request)
  function edit($uuid, $currentPage = 1)
  function update(App\Http\Requests\Admin\CateNewRequest $request, string $uuid)
  function status($uuid, $status, $field)
  function numericalOrder(Illuminate\Http\Request $request, $uuid)
  function destroy(string $uuid)
  function destroyAll(Illuminate\Http\Request $request)
  ```

### `class CateProductController`
- **Namespace**: `\App\Http\Controllers\Admin`
- **Hierarchy**: `CateProductController extends BaseController`
- **Public API Methods**:
  ```php
  function index(Illuminate\Http\Request $request)
  function create()
  function store(App\Http\Requests\Admin\CateProductRequest $request)
  function edit($uuid, $currentPage = 1)
  function update(App\Http\Requests\Admin\CateProductRequest $request, string $uuid)
  function numericalOrder(Illuminate\Http\Request $request, $uuid)
  function status($uuid, $status, $field)
  function destroy(string $uuid)
  function destroyAll(Illuminate\Http\Request $request)
  ```

### `class CateProjectController`
- **Namespace**: `\App\Http\Controllers\Admin`
- **Hierarchy**: `CateProjectController extends BaseController`
- **Public API Methods**:
  ```php
  function index(Illuminate\Http\Request $request)
  function create()
  function store(App\Http\Requests\Admin\CateProjectRequest $request)
  function edit($uuid, $currentPage = 1)
  function update(App\Http\Requests\Admin\CateProjectRequest $request, string $uuid)
  function status($uuid, $status, $field)
  function numericalOrder(Illuminate\Http\Request $request, $uuid)
  function destroy(string $uuid)
  function destroyAll(Illuminate\Http\Request $request)
  ```

### `class CateServiceController`
- **Namespace**: `\App\Http\Controllers\Admin`
- **Hierarchy**: `CateServiceController extends BaseController`
- **Public API Methods**:
  ```php
  function index(Illuminate\Http\Request $request)
  function create()
  function store(App\Http\Requests\Admin\CateServiceRequest $request)
  function edit($uuid, $currentPage = 1)
  function update(App\Http\Requests\Admin\CateServiceRequest $request, string $uuid)
  function status($uuid, $status, $field)
  function numericalOrder(Illuminate\Http\Request $request, $uuid)
  function destroy(string $uuid)
  function destroyAll(Illuminate\Http\Request $request)
  ```

### `class ChatController`
- **Namespace**: `\App\Http\Controllers\Admin`
- **Hierarchy**: `ChatController extends BaseController`
- **Public API Methods**:
  ```php
  function index(Illuminate\Http\Request $request)
  function show($uuid)
  function sendMessage(Illuminate\Http\Request $request, $uuid): Illuminate\Http\JsonResponse
  function getNewMessages(Illuminate\Http\Request $request, $uuid): Illuminate\Http\JsonResponse
  function closeSession($uuid)
  function getUnreadCount(): Illuminate\Http\JsonResponse
  function bulkClose(Illuminate\Http\Request $request)
  ```

### `class CommentController`
- **Namespace**: `\App\Http\Controllers\Admin`
- **Hierarchy**: `CommentController extends BaseController`
- **Public API Methods**:
  ```php
  function index(Illuminate\Http\Request $request)
  function status($uuid, $status, $field)
  function edit($uuid)
  function update(Illuminate\Http\Request $request, $uuid)
  function destroy(string $uuid)
  function destroyAll(Illuminate\Http\Request $request)
  ```

### `class ContactController`
- **Namespace**: `\App\Http\Controllers\Admin`
- **Hierarchy**: `ContactController extends BaseController`
- **Public API Methods**:
  ```php
  function index(Illuminate\Http\Request $request)
  function status($uuid, $status, $field)
  function edit($uuid)
  function destroy(string $uuid)
  function destroyAll(Illuminate\Http\Request $request)
  ```

### `class FeatureController`
- **Namespace**: `\App\Http\Controllers\Admin`
- **Hierarchy**: `FeatureController extends BaseController`
- **Public API Methods**:
  ```php
  function index(Illuminate\Http\Request $request)
  function create()
  function store(App\Http\Requests\Admin\FeatureRequest $request)
  function edit($uuid, $currentPage = 1)
  function update(App\Http\Requests\Admin\FeatureRequest $request, string $uuid)
  function status($uuid, $status, $field)
  function destroy(string $uuid)
  function destroyAll(Illuminate\Http\Request $request)
  function numericalOrder(Illuminate\Http\Request $request, $uuid)
  ```

### `class FeedBackController`
- **Namespace**: `\App\Http\Controllers\Admin`
- **Hierarchy**: `FeedBackController extends BaseController`
- **Public API Methods**:
  ```php
  function index(Illuminate\Http\Request $request)
  function create()
  function store(App\Http\Requests\Admin\FeedBackRequest $request)
  function edit($uuid, $currentPage = 1)
  function update(App\Http\Requests\Admin\FeedBackRequest $request, string $uuid)
  function destroy(string $uuid)
  function destroyAll(Illuminate\Http\Request $request)
  function status($uuid, $status, $field)
  function numericalOrder(Illuminate\Http\Request $request, $uuid)
  ```

### `class FileManagerController`
- **Namespace**: `\App\Http\Controllers\Admin`
- **Hierarchy**: `FileManagerController extends Controller`
- **Public API Methods**:
  ```php
  function index(Illuminate\Http\Request $request)
  function upload(Illuminate\Http\Request $request)
  function delete(Illuminate\Http\Request $request)
  function createFolder(Illuminate\Http\Request $request)
  function ckeditor(Illuminate\Http\Request $request)
  ```

### `class FontController`
- **Namespace**: `\App\Http\Controllers\Admin`
- **Hierarchy**: `FontController extends BaseController`
- **Public API Methods**:
  ```php
  function getActiveFonts()
  function index(Illuminate\Http\Request $request)
  function create()
  function store(Illuminate\Http\Request $request)
  function edit($id)
  function update(Illuminate\Http\Request $request, $id)
  function destroy($id)
  function destroyAll(Illuminate\Http\Request $request)
  ```

### `class GalleryController`
- **Namespace**: `\App\Http\Controllers\Admin`
- **Hierarchy**: `GalleryController extends BaseController`
- **Public API Methods**:
  ```php
  function index(Illuminate\Http\Request $request)
  function create()
  function store(App\Http\Requests\Admin\GalleryRequest $request)
  function edit($uuid, $currentPage = 1)
  function update(App\Http\Requests\Admin\GalleryRequest $request, string $uuid)
  function status($uuid, $status, $field)
  function destroy(string $uuid)
  function destroyAll(Illuminate\Http\Request $request)
  function numericalOrder(Illuminate\Http\Request $request, $uuid)
  ```

### `class LfmUploadController`
- **Namespace**: `\App\Http\Controllers\Admin`
- **Hierarchy**: `LfmUploadController extends UploadController`
- **Public API Methods**:
  ```php
  function upload()
  ```

### `class MailConfigController`
- **Namespace**: `\App\Http\Controllers\Admin`
- **Hierarchy**: `MailConfigController extends BaseController`
- **Public API Methods**:
  ```php
  function index()
  function create()
  function store(App\Http\Requests\Admin\MailConfigRequest $request)
  function edit($id)
  function update($id, App\Http\Requests\Admin\MailConfigRequest $request)
  function setActive($id)
  function testConfig($id)
  function destroy($id)
  function destroyAll(Illuminate\Http\Request $request)
  ```

### `class MailTemplateController`
- **Namespace**: `\App\Http\Controllers\Admin`
- **Hierarchy**: `MailTemplateController extends BaseController`
- **Public API Methods**:
  ```php
  function index()
  function create()
  function store(App\Http\Requests\Admin\MailTemplateRequest $request)
  function edit($id)
  function update($id, App\Http\Requests\Admin\MailTemplateRequest $request)
  function setActive($id)
  function destroy($id)
  function destroyAll(Illuminate\Http\Request $request)
  ```

### `class MediaController`
- **Namespace**: `\App\Http\Controllers\Admin`
- **Hierarchy**: `MediaController extends Controller`
- **Public API Methods**:
  ```php
  function index(Illuminate\Http\Request $request)
  function list(Illuminate\Http\Request $request): Illuminate\Http\JsonResponse
  function upload(Illuminate\Http\Request $request): Illuminate\Http\JsonResponse
  function delete(Illuminate\Http\Request $request, $id): Illuminate\Http\JsonResponse
  function modules(): Illuminate\Http\JsonResponse
  ```

### `class MenuController`
- **Namespace**: `\App\Http\Controllers\Admin`
- **Hierarchy**: `MenuController extends BaseController`
- **Public API Methods**:
  ```php
  function status($uuid, $status, $field)
  function index()
  function store(App\Http\Requests\Admin\MenuRequest $request)
  function reorder(Illuminate\Http\Request $request)
  function update(Illuminate\Http\Request $request, string $uuid)
  function destroy(string $uuid)
  function destroyAll(Illuminate\Http\Request $request)
  function numericalOrder(Illuminate\Http\Request $request, $uuid)
  ```

### `class NewsController`
- **Namespace**: `\App\Http\Controllers\Admin`
- **Hierarchy**: `NewsController extends BaseController`
- **Public API Methods**:
  ```php
  function index(Illuminate\Http\Request $request)
  function create()
  function store(App\Http\Requests\Admin\NewsRequest $request)
  function edit($uuid, $currentPage = 1)
  function update(App\Http\Requests\Admin\NewsRequest $request, string $uuid)
  function status($uuid, $status, $field)
  function numericalOrder(Illuminate\Http\Request $request, $uuid)
  function destroy(string $uuid)
  function destroyAll(Illuminate\Http\Request $request)
  ```

### `class NewsSettingController`
- **Namespace**: `\App\Http\Controllers\Admin`
- **Hierarchy**: `NewsSettingController extends BaseController`
- **Public API Methods**:
  ```php
  function index()
  function updateSettings(Illuminate\Http\Request $request)
  ```

### `class OrderController`
- **Namespace**: `\App\Http\Controllers\Admin`
- **Hierarchy**: `OrderController extends BaseController`
- **Public API Methods**:
  ```php
  function index(Illuminate\Http\Request $request)
  function status($uuid, $status, $field)
  function edit($uuid)
  function destroy_order($uuid)
  ```

### `class PageController`
- **Namespace**: `\App\Http\Controllers\Admin`
- **Hierarchy**: `PageController extends BaseController`
- **Public API Methods**:
  ```php
  function index(Illuminate\Http\Request $request)
  function create()
  function store(App\Http\Requests\Admin\PageRequest $request)
  function edit($uuid, $currentPage = 1)
  function update(App\Http\Requests\Admin\PageRequest $request, string $uuid)
  function status($uuid, $status, $field)
  function numericalOrder(Illuminate\Http\Request $request, $uuid)
  function destroy(string $uuid)
  function destroyAll(Illuminate\Http\Request $request)
  ```

### `class ProductController`
- **Namespace**: `\App\Http\Controllers\Admin`
- **Hierarchy**: `ProductController extends BaseController`
- **Public API Methods**:
  ```php
  function index(Illuminate\Http\Request $request)
  function create()
  function store(App\Http\Requests\Admin\ProductRequest $request)
  function edit($uuid, $currentPage = 1)
  function update(App\Http\Requests\Admin\ProductRequest $request, string $uuid)
  function status($uuid, $status, $field)
  function numericalOrder(Illuminate\Http\Request $request, $uuid)
  function destroy(string $uuid)
  function destroyAll(Illuminate\Http\Request $request)
  ```

### `class ProductSettingController`
- **Namespace**: `\App\Http\Controllers\Admin`
- **Hierarchy**: `ProductSettingController extends BaseController`
- **Public API Methods**:
  ```php
  function index()
  function updateSettings(Illuminate\Http\Request $request)
  ```

### `class ProfileController`
- **Namespace**: `\App\Http\Controllers\Admin`
- **Hierarchy**: `ProfileController extends BaseController`
- **Public API Methods**:
  ```php
  function index()
  function update($uuid, App\Http\Requests\Admin\ProfileRequest $request)
  function changePassword(Illuminate\Http\Request $request)
  ```

### `class ProjectController`
- **Namespace**: `\App\Http\Controllers\Admin`
- **Hierarchy**: `ProjectController extends BaseController`
- **Public API Methods**:
  ```php
  function index(Illuminate\Http\Request $request)
  function create()
  function store(App\Http\Requests\Admin\ProjectRequest $request)
  function edit($uuid, $currentPage = 1)
  function update(App\Http\Requests\Admin\ProjectRequest $request, string $uuid)
  function status($uuid, $status, $field)
  function numericalOrder(Illuminate\Http\Request $request, $uuid)
  function destroy(string $uuid)
  function destroyAll(Illuminate\Http\Request $request)
  ```

### `class ServiceController`
- **Namespace**: `\App\Http\Controllers\Admin`
- **Hierarchy**: `ServiceController extends BaseController`
- **Public API Methods**:
  ```php
  function index(Illuminate\Http\Request $request)
  function create()
  function store(App\Http\Requests\Admin\ServiceRequest $request)
  function edit($uuid, $currentPage = 1)
  function update(App\Http\Requests\Admin\ServiceRequest $request, string $uuid)
  function status($uuid, $status, $field)
  function numericalOrder(Illuminate\Http\Request $request, $uuid)
  function destroy(string $uuid)
  function destroyAll(Illuminate\Http\Request $request)
  ```

### `class SitemapController`
- **Namespace**: `\App\Http\Controllers\Admin`
- **Hierarchy**: `SitemapController extends Controller`
- **Public API Methods**:
  ```php
  function generate(Illuminate\Http\Request $request)
  ```

### `class SliderController`
- **Namespace**: `\App\Http\Controllers\Admin`
- **Hierarchy**: `SliderController extends BaseController`
- **Public API Methods**:
  ```php
  function index(Illuminate\Http\Request $request)
  function create()
  function store(App\Http\Requests\Admin\SliderRequest $request)
  function edit($uuid, $currentPage = 1)
  function update(App\Http\Requests\Admin\SliderRequest $request, string $uuid)
  function status($uuid, $status, $field)
  function destroy(string $uuid)
  function destroyAll(Illuminate\Http\Request $request)
  function numericalOrder(Illuminate\Http\Request $request, $uuid)
  ```

### `class SystemController`
- **Namespace**: `\App\Http\Controllers\Admin`
- **Hierarchy**: `SystemController extends BaseController`
- **Public API Methods**:
  ```php
  function index()
  function update($id, App\Http\Requests\Admin\SystemRequest $request)
  function clearCache()
  function editContact()
  function updateContact($id, Illuminate\Http\Request $request)
  ```

### `class UserController`
- **Namespace**: `\App\Http\Controllers\Admin`
- **Hierarchy**: `UserController extends BaseController`
- **Public API Methods**:
  ```php
  function index(Illuminate\Http\Request $request)
  function create()
  function edit($uuid)
  function update(App\Http\Requests\Admin\UserRequest $request, $uuid)
  function destroy($uuid)
  function bulkDelete(Illuminate\Http\Request $request)
  ```

### `class UserRoleController`
- **Namespace**: `\App\Http\Controllers\Admin`
- **Hierarchy**: `UserRoleController extends Controller`
- **Public API Methods**:
  ```php
  function index(Illuminate\Http\Request $request)
  function edit($id)
  function updateRoles(Illuminate\Http\Request $request, $id)
  function bulkUpdateRoles(Illuminate\Http\Request $request)
  function roleIndex()
  function roleCreate()
  function roleStore(Illuminate\Http\Request $request)
  function roleEdit($id)
  function roleUpdate(Illuminate\Http\Request $request, $id)
  function roleDestroy($id)
  ```

---

## Controllers (Root & Auth)

### `class Controller`
- **Namespace**: `\App\Http\Controllers`
- **Hierarchy**: `Controller extends Controller`
- **Public API Methods**:
  ```php
  function authorize($ability, $arguments = [])
  function authorizeForUser($user, $ability, $arguments = [])
  function authorizeResource($model, $parameter = null, array $options = [], $request = null)
  function validateWith($validator, ?Illuminate\Http\Request $request = null)
  function validate(Illuminate\Http\Request $request, array $rules, array $messages = [], array $attributes = [])
  function validateWithBag($errorBag, Illuminate\Http\Request $request, array $rules, array $messages = [], array $attributes = [])
  ```

---

## Requests

### `class AboutRequest`
- **Namespace**: `\App\Http\Requests\Admin`
- **Hierarchy**: `AboutRequest extends BaseAdminRequest implements Stringable, Arrayable, ArrayAccess, ValidatesWhenResolved`
- **Public API Methods**:
  ```php
  function rules(): array
  function messages(): array
  ```

### `abstract class BaseAdminRequest`
- **Namespace**: `\App\Http\Requests\Admin`
- **Hierarchy**: `BaseAdminRequest extends FormRequest implements ValidatesWhenResolved, ArrayAccess, Arrayable, Stringable`
- **Public API Methods**:
  ```php
  function authorize(): bool
  function messages(): array
  function attributes(): array
  function imageRule(bool $required = false): array
  ```

### `class BrandRequest`
- **Namespace**: `\App\Http\Requests\Admin`
- **Hierarchy**: `BrandRequest extends BaseAdminRequest implements Stringable, Arrayable, ArrayAccess, ValidatesWhenResolved`
- **Public API Methods**:
  ```php
  function rules(): array
  function messages(): array
  ```

### `class CateNewRequest`
- **Namespace**: `\App\Http\Requests\Admin`
- **Hierarchy**: `CateNewRequest extends BaseAdminRequest implements Stringable, Arrayable, ArrayAccess, ValidatesWhenResolved`
- **Public API Methods**:
  ```php
  function rules(): array
  function messages(): array
  ```

### `class CateProductRequest`
- **Namespace**: `\App\Http\Requests\Admin`
- **Hierarchy**: `CateProductRequest extends BaseAdminRequest implements Stringable, Arrayable, ArrayAccess, ValidatesWhenResolved`
- **Public API Methods**:
  ```php
  function rules(): array
  function messages(): array
  ```

### `class CateProjectRequest`
- **Namespace**: `\App\Http\Requests\Admin`
- **Hierarchy**: `CateProjectRequest extends BaseAdminRequest implements Stringable, Arrayable, ArrayAccess, ValidatesWhenResolved`
- **Public API Methods**:
  ```php
  function rules(): array
  function messages(): array
  ```

### `class CateServiceRequest`
- **Namespace**: `\App\Http\Requests\Admin`
- **Hierarchy**: `CateServiceRequest extends BaseAdminRequest implements Stringable, Arrayable, ArrayAccess, ValidatesWhenResolved`
- **Public API Methods**:
  ```php
  function rules(): array
  function messages(): array
  ```

### `class CommentRequest`
- **Namespace**: `\App\Http\Requests\Admin`
- **Hierarchy**: `CommentRequest extends FormRequest implements ValidatesWhenResolved, ArrayAccess, Arrayable, Stringable`
- **Public API Methods**:
  ```php
  function authorize(): bool
  function rules(): array
  ```

### `class FeatureRequest`
- **Namespace**: `\App\Http\Requests\Admin`
- **Hierarchy**: `FeatureRequest extends BaseAdminRequest implements Stringable, Arrayable, ArrayAccess, ValidatesWhenResolved`
- **Public API Methods**:
  ```php
  function rules(): array
  function messages(): array
  ```

### `class FeedBackRequest`
- **Namespace**: `\App\Http\Requests\Admin`
- **Hierarchy**: `FeedBackRequest extends BaseAdminRequest implements Stringable, Arrayable, ArrayAccess, ValidatesWhenResolved`
- **Public API Methods**:
  ```php
  function rules(): array
  function messages(): array
  ```

### `class GalleryRequest`
- **Namespace**: `\App\Http\Requests\Admin`
- **Hierarchy**: `GalleryRequest extends BaseAdminRequest implements Stringable, Arrayable, ArrayAccess, ValidatesWhenResolved`
- **Public API Methods**:
  ```php
  function rules(): array
  function messages(): array
  ```

### `class MailConfigRequest`
- **Namespace**: `\App\Http\Requests\Admin`
- **Hierarchy**: `MailConfigRequest extends BaseAdminRequest implements Stringable, Arrayable, ArrayAccess, ValidatesWhenResolved`
- **Public API Methods**:
  ```php
  function authorize(): bool
  function rules(): array
  function messages(): array
  function attributes(): array
  ```

### `class MailTemplateRequest`
- **Namespace**: `\App\Http\Requests\Admin`
- **Hierarchy**: `MailTemplateRequest extends FormRequest implements ValidatesWhenResolved, ArrayAccess, Arrayable, Stringable`
- **Public API Methods**:
  ```php
  function authorize(): bool
  function rules(): array
  function messages(): array
  ```

### `class MenuRequest`
- **Namespace**: `\App\Http\Requests\Admin`
- **Hierarchy**: `MenuRequest extends BaseAdminRequest implements Stringable, Arrayable, ArrayAccess, ValidatesWhenResolved`
- **Public API Methods**:
  ```php
  function authorize(): bool
  function rules(): array
  function messages(): array
  ```

### `class NewsRequest`
- **Namespace**: `\App\Http\Requests\Admin`
- **Hierarchy**: `NewsRequest extends BaseAdminRequest implements Stringable, Arrayable, ArrayAccess, ValidatesWhenResolved`
- **Public API Methods**:
  ```php
  function rules(): array
  function messages(): array
  ```

### `class PageRequest`
- **Namespace**: `\App\Http\Requests\Admin`
- **Hierarchy**: `PageRequest extends BaseAdminRequest implements Stringable, Arrayable, ArrayAccess, ValidatesWhenResolved`
- **Public API Methods**:
  ```php
  function rules(): array
  function messages(): array
  ```

### `class ProductRequest`
- **Namespace**: `\App\Http\Requests\Admin`
- **Hierarchy**: `ProductRequest extends BaseAdminRequest implements Stringable, Arrayable, ArrayAccess, ValidatesWhenResolved`
- **Public API Methods**:
  ```php
  function rules(): array
  function messages(): array
  ```

### `class ProductSettingRequest`
- **Namespace**: `\App\Http\Requests\Admin`
- **Hierarchy**: `ProductSettingRequest extends BaseAdminRequest implements Stringable, Arrayable, ArrayAccess, ValidatesWhenResolved`
- **Public API Methods**:
  ```php
  function authorize(): bool
  function rules(): array
  function messages(): array
  function attributes(): array
  ```

### `class ProfileRequest`
- **Namespace**: `\App\Http\Requests\Admin`
- **Hierarchy**: `ProfileRequest extends BaseAdminRequest implements Stringable, Arrayable, ArrayAccess, ValidatesWhenResolved`
- **Public API Methods**:
  ```php
  function rules(): array
  function messages(): array
  ```

### `class ProjectRequest`
- **Namespace**: `\App\Http\Requests\Admin`
- **Hierarchy**: `ProjectRequest extends BaseAdminRequest implements Stringable, Arrayable, ArrayAccess, ValidatesWhenResolved`
- **Public API Methods**:
  ```php
  function rules(): array
  function messages(): array
  ```

### `class ServiceRequest`
- **Namespace**: `\App\Http\Requests\Admin`
- **Hierarchy**: `ServiceRequest extends BaseAdminRequest implements Stringable, Arrayable, ArrayAccess, ValidatesWhenResolved`
- **Public API Methods**:
  ```php
  function rules(): array
  function messages(): array
  ```

### `class SiteSettingRequest`
- **Namespace**: `\App\Http\Requests\Admin`
- **Hierarchy**: `SiteSettingRequest extends BaseAdminRequest implements Stringable, Arrayable, ArrayAccess, ValidatesWhenResolved`
- **Public API Methods**:
  ```php
  function rules(): array
  function messages(): array
  ```

### `class SliderRequest`
- **Namespace**: `\App\Http\Requests\Admin`
- **Hierarchy**: `SliderRequest extends BaseAdminRequest implements Stringable, Arrayable, ArrayAccess, ValidatesWhenResolved`
- **Public API Methods**:
  ```php
  function rules(): array
  function messages(): array
  ```

### `class SystemRequest`
- **Namespace**: `\App\Http\Requests\Admin`
- **Hierarchy**: `SystemRequest extends BaseAdminRequest implements Stringable, Arrayable, ArrayAccess, ValidatesWhenResolved`
- **Public API Methods**:
  ```php
  function authorize(): bool
  function rules(): array
  function messages(): array
  function attributes(): array
  ```

### `class UserRequest`
- **Namespace**: `\App\Http\Requests\Admin`
- **Hierarchy**: `UserRequest extends FormRequest implements ValidatesWhenResolved, ArrayAccess, Arrayable, Stringable`
- **Public API Methods**:
  ```php
  function authorize(): bool
  function rules(): array
  function messages(): array
  ```

### `class LoginUserRequest`
- **Namespace**: `\App\Http\Requests\Auth`
- **Hierarchy**: `LoginUserRequest extends FormRequest implements ValidatesWhenResolved, ArrayAccess, Arrayable, Stringable`
- **Public API Methods**:
  ```php
  function authorize()
  function rules()
  function messages()
  ```

### `class RegisterUserRequest`
- **Namespace**: `\App\Http\Requests\Auth`
- **Hierarchy**: `RegisterUserRequest extends FormRequest implements ValidatesWhenResolved, ArrayAccess, Arrayable, Stringable`
- **Public API Methods**:
  ```php
  function authorize()
  function rules()
  function messages()
  ```

### `class ResetPasswordRequest`
- **Namespace**: `\App\Http\Requests\Auth`
- **Hierarchy**: `ResetPasswordRequest extends FormRequest implements ValidatesWhenResolved, ArrayAccess, Arrayable, Stringable`
- **Public API Methods**:
  ```php
  function authorize()
  function rules()
  function messages()
  ```

### `class SendOtpRequest`
- **Namespace**: `\App\Http\Requests\Auth`
- **Hierarchy**: `SendOtpRequest extends FormRequest implements ValidatesWhenResolved, ArrayAccess, Arrayable, Stringable`
- **Public API Methods**:
  ```php
  function authorize()
  function rules()
  function messages()
  ```

### `class VerifyOtpRequest`
- **Namespace**: `\App\Http\Requests\Auth`
- **Hierarchy**: `VerifyOtpRequest extends FormRequest implements ValidatesWhenResolved, ArrayAccess, Arrayable, Stringable`
- **Public API Methods**:
  ```php
  function authorize()
  function rules()
  function messages()
  ```

### `class ContactRequest`
- **Namespace**: `\App\Http\Requests\Frontend`
- **Hierarchy**: `ContactRequest extends FormRequest implements ValidatesWhenResolved, ArrayAccess, Arrayable, Stringable`
- **Public API Methods**:
  ```php
  function authorize(): bool
  function rules(): array
  function messages(): array
  ```

### `class LoginRequest`
- **Namespace**: `\App\Http\Requests\Login`
- **Hierarchy**: `LoginRequest extends FormRequest implements ValidatesWhenResolved, ArrayAccess, Arrayable, Stringable`
- **Public API Methods**:
  ```php
  function authorize()
  function rules()
  function messages()
  ```

---

## Traits

### `trait CrudOperationsTrait`
- **Namespace**: `\App\Traits\Admin`
- **Public API Methods**:
  ```php
  function create()
  ```

### `trait AutoImagePathsTrait`
- **Namespace**: `\App\Traits`
- **Public API Methods**:
  ```php
  function getImageVnAttribute($value)
  function getImageEnAttribute($value)
  function getImageAttribute($value = null)
  function getAvatarAttribute($value)
  function getLogoAttribute($value)
  function getFaviconAttribute($value)
  function getShareImageAttribute($value)
  ```

### `trait BaseLogsActivity`
- **Namespace**: `\App\Traits`
- **Public API Methods**:
  ```php
  function getActivitylogOptions(): Spatie\Activitylog\LogOptions
  static function addLogChange(Spatie\Activitylog\Contracts\LoggablePipe $pipe): void
  function isLogEmpty(array $changes): bool
  function disableLogging(): self
  function enableLogging(): self
  function activities(): Illuminate\Database\Eloquent\Relations\MorphMany
  function getDescriptionForEvent(string $eventName): string
  function getLogNameToUse(): ?string
  function attributesToBeLogged(): array
  function shouldLogUnguarded(): bool
  function attributeValuesToBeLogged(string $processingEvent): array
  static function logChanges(Illuminate\Database\Eloquent\Model $model): array
  ```

### `trait DataRemovalTrait`
- **Namespace**: `\App\Traits`
- **Public API Methods**:
  ```php
  function destroy(string $uuid)
  function destroyAll(Illuminate\Http\Request $request)
  ```

### `trait HasRoles`
- **Namespace**: `\App\Traits`
- **Public API Methods**:
  ```php
  function roles(): Illuminate\Database\Eloquent\Relations\BelongsToMany
  function directPermissions(): Illuminate\Database\Eloquent\Relations\BelongsToMany
  function isSuperAdmin(): bool
  function permissions()
  function getAllPermissions()
  function getPermissionsAttribute()
  function assignRole($roles): void
  function givePermissionTo($permissions): void
  function syncDirectPermissions($permissions): void
  function removeRole($roles): void
  function syncRoles($roles): void
  function hasRole($roles): bool
  function hasAllRoles($roles): bool
  function hasPermission($permission): bool
  function hasAllPermissions($permissions): bool
  function hasAnyPermission($permissions): bool
  function forgetCachedPermissions(): void
  function getPermissionsAttributeAttribute()
  function getUserPermissions()
  ```

### `trait ImageHandlerTrait`
- **Namespace**: `\App\Traits`

### `trait SlugHandlerTrait`
- **Namespace**: `\App\Traits`

---

