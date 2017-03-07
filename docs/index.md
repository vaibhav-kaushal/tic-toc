# What is Tic-Toc?

GitHub published that they now allow you to publish your code's documentation using GitHub Pages ([link to announcement](https://github.com/blog/2233-publish-your-project-documentation-with-github-pages)). However, it involves manually setting up an `index.md` file in your `/docs` folder and then telling GitHub to generate the [pages](http://pages.github.com) site using the contents of `/docs` folder.

This effort by GitHub is not only noble but also provides for better documentation alongside code. No need to have a second repo or website where you have to update documentation of your project. Hence, every pull request can now automatically have the required changes to documentation. Also, documentation can be done in Markdown which is the most popular markup language being used for the purpose. Submitting documentation becomes easy and as maintainable as code itself. Brilliant! However there is a catch: To make the documentation navigable, you have to do the linking manually. Since new documentation can be added rapidly, existing ones moved and renamed while some old ones might be deleted.

Tic-Toc is a easy-to-use system which can create a TOC (Table of Contents) from a folder inside which the documents reside. Its aim is to:

- [ ] Create a master TOC for all `.md` files in the given folder.
- [ ] Create a TOC for all individual folders beneath it.
- [ ] Link the main TOC and folder level TOCs in each `.md` file it parses.
- [ ] Create a navigation breadcrumb at the top of each `.md` document.


## ATTENTION!

The TOC files will bear the name of `toc.md`. So if you already have any file with that name in the directory where you would run tic-toc, they would be overwritten!

----
[Table of Contents](toc.md)
