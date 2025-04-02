-- Insert first category
INSERT INTO categories (name, slug, description) VALUES 
('Announcements', 'announcements', 'Important updates and announcements about Root Labs');

-- Insert first post
INSERT INTO posts (
    title, 
    slug, 
    content, 
    excerpt, 
    category_id, 
    author_id, 
    status, 
    created_at, 
    updated_at
) VALUES (
    'Welcome to Root Labs Blog',
    'welcome-to-root-labs-blog',
    '# Welcome to Root Labs Blog

We are excited to launch our new blog! This space will serve as a platform for sharing our journey, technical insights, and updates about our projects.

## What to Expect

Our blog will cover various topics including:

- Technical deep dives into our projects
- Industry insights and analysis
- Tutorial content and how-to guides
- Company updates and announcements

## Stay Connected

Make sure to check back regularly for new content. You can also follow us on our social media channels for updates.

We look forward to sharing our journey with you!',
    'Welcome to the Root Labs blog! This is where we will share our journey, technical insights, and updates about our projects.',
    (SELECT id FROM categories WHERE slug = 'announcements'),
    1, -- Assuming the first admin user has ID 1
    'published',
    NOW(),
    NOW()
);

-- Insert tags
INSERT INTO tags (name, slug) VALUES 
('announcement', 'announcement'),
('root-labs', 'root-labs');

-- Link tags to post
INSERT INTO post_tags (post_id, tag_id)
SELECT 
    (SELECT id FROM posts WHERE slug = 'welcome-to-root-labs-blog'),
    id 
FROM tags 
WHERE slug IN ('announcement', 'root-labs'); 