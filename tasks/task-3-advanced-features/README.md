# Task 3 - Advanced Features Implementation

## Objective

Search, pagination, and improved UI on the posts listing.

## Implemented

- **Search:** `posts/index.php` — `q` query param; SQL `WHERE title LIKE … OR content LIKE …` with `escapeLikePattern()` in `includes/search.php`
- **Pagination:** 5 posts per page (`POSTS_PER_PAGE`); numbered links + Previous/Next; `q` preserved when changing pages
- **UI:** Search bar, page meta line, pagination nav, card hover, mobile-friendly layout (`assets/style.css`)

## Status

Completed.
