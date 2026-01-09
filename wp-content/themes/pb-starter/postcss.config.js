module.exports = {
  plugins: [
    // Import other CSS files
    require('postcss-import'),
    
    // SCSS-style variables ($variable)
    // Must come BEFORE nesting so variables work inside nested rules
    require('postcss-simple-vars'),
    
    // Enable CSS nesting (like SCSS)
    require('postcss-nesting'),
    
    // Add vendor prefixes
    require('autoprefixer'),
    
    // Minify in production
    ...(process.env.NODE_ENV === 'production' 
      ? [require('cssnano')({
          preset: ['default', {
            discardComments: {
              removeAll: true,
            },
          }]
        })]
      : []
    ),
  ],
};