// tailwind.config.js
module.exports = {
  purge: ["./pages/*.html"],
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
        padding: "11.25px",
        screens: {
          sm: "576px",
          md: "768px",
          lg: "992px",
          xl: "1200px",
          "2xl": "1320px",
        },
      },
      colors: {
        primay: {
          black: {
            100: "#231F20",
            200: "#838282",
          },
          white: {
            100: "#EEEEEE",
            200: "#F6F6F6",
            300: "#D9D9D9",
            400: "#E0E0E0",
            500: "#AEAEAE",
            600: "#FAFAFA",
          },
          brown: {
            100: "#AF8D53",
            200: "#CCB086",
          }
        }
      },
      fontFamily: {
        kalameh: ["Kalameh"],
        kalameh_fa: ["KalamehFa"],
      },
      boxShadow: {
          "custom" : "0 2px 10px 0 rgba(0, 0, 0, 0.20)"
      },
      borderRadius: {
        "custom" : "1.25rem"
      }
    },
  },
  variants: {},
  plugins: [],
};
