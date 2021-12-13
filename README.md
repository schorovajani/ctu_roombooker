# BI-TWA – Semestral work

Team: @dvora124, @paukeluk and @schorjan

---

# FAQ

## How to rebase after squash merge?

The situation is as follows.
A `fix` branch has been put up for a merge request, however, you'd like start working on a `feature` branch, which builds on top of the changes introduced in `fix`.
While working on `feature`, the merge request for `fix` is accepted and the changes are merged into `master` **using the squash merge strategy**.

You would now like to rebase `feature` to synchronize with the new changes, but there's a problem.
The history of `feature` includes several separate commits from `fix` while on `master`, they are all squashed into a single commit.
If you try to rebase now, the operation will fail due to incompatible histories.

The ASCII art below illustrates the problem.

```text
       o <- (feature)                     o <- (feature)
       |                                  |
       o                  (master) -> o   o
      /                               |\ /
     o <- (fix)                       | o <- (fix)
     |                                | |
     o                                | o
    /                                 |/
   o <- (master)                      o
   |                                  |
   o                                  o
```

There are 3 ways to solve this problem.

### 1. Using `cherry-pick`

Create a new branch which branches off of `master`, then copy all unique commits from the `feature` to this new branch.

Details: https://www.nrmitchi.com/2019/02/rebase-after-a-squash/

### 2. Using `rebase --interactive`

Using interactive rebase (`git branch -i master`), delete unwanted separate commits in the rebase-todo list before continuing.

Details: https://www.nrmitchi.com/2019/02/rebase-after-a-squash/

### 3. Using `rebase --onto`

Using `rebase --onto` we can perform the following:

> "Rebase the `feature` branch by taking the range of commits beginning at `fix` (excluded) and ending at `feature` (included) and give them a new parent commit at `master`."

The solution is `git rebase --onto master fix feature`.

Related: `man git-rebase`  
Related: https://tech.bakkenbaeck.com/post/Rebasing_Onto_A_Squashed_Commit
