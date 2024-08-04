// tailwind.config.js
module.exports = {
  purge: ["./**/*.php"],
  darkMode: false, // or 'media' or 'class'
  theme: {
    extend: {
      screens: {
        sm: "576px",
        md: "768px",
        lg: "992px",
        xl: "1200px",
        "2xl": "1320px",
      },
      container: {
        center: true,
        padding: "16px",
        screens: {
          sm: "576px",
          md: "768px",
          lg: "992px",
          xl: "1200px",
          "2xl": "1320px",
        },
      },
      colors: {
        primay: {}
      },
    },
  },
  variants: {},
  plugins: [],
};
