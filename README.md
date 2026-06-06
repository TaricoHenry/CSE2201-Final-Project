# README File for CSE2201 - Final Project

## Link to Repo
[View the GitHub Repository](https://github.com/TaricoHenry/CSE2201-Final-Project)
## Link to Hosted Website
[View the Live Website](https://taricohenry.github.io/CSE2201-Final-Project/)


## Getting Started Locally

To get this project spun up locally. Just follow the steps:

1. Clone the repository:

   ```bash
   git clone https://github.com/TaricoHenry/CSE2201-Final-Project.git
   ```

2. Navigate into the project folder:

   ```bash
   cd CSE2201-Final-Project
   ```
   
   This project has two main parts:

- The Jekyll website is in `jekyll/CSE2201-project/`
- The Firebase backend is in `firbase/`

3. Open the project in your code editor.

4. Install all requirements:

- Node.js and npm
- Ruby and Bundler
- Firebase CLI

5. Spin up jekyll site locally


```bash
cd jekyll/CSE2201-project
```

Install the Ruby dependencies:

```bash
bundle install
```

Start the Jekyll website:

```bash
bundle exec jekyll serve
```

local site is at the host

```text
http://127.0.0.1:4000/CSE2201-Final-Project/
```

6. Spin up the firebase backend locally
```bash
cd firbase/functions
```

Install the Node dependencies:

```bash
npm install
```

Go back to the Firebase folder:

```bash
cd ..
```

Start the local emulators:

```bash
firebase emulators:start --only functions,firestore
```

The local health route can be tested at:

```text
http://127.0.0.1:5001/cse2201-project/us-central1/api/api/v1/health
```

6. Seed some test data to the data and have fun. 