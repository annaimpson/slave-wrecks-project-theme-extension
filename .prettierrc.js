module.exports = {
  printWidth: 80,
  tabWidth: 4,
  useTabs: false,
  singleQuote: true,
  jsxBracketSameLine: true,
  overrides: [
    {
      files: ['*.js', '*.json'],
      options: {
        tabWidth: 2,
        trailingComma: 'es5',
      },
    },
    {
      files: '*.php',
      options: {
        trailingCommaPHP: false,
        braceStyle: '1tbs',
        requirePragma: false,
        insertPragma: false,
        printWidth: 120,
      },
    },
  ],
};
