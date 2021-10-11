export const getFriendlyLifetime = (createdAt) => {
  const currentTime = Date.now();
  const creationTime = new Date(createdAt).getTime();

  // Compute time difference in milliseconds
  let timeDiff = currentTime - creationTime;

  // Convert time difference from milliseconds to seconds
  timeDiff = timeDiff / 1000;

  // Convert time difference from seconds to minutes
  timeDiff = timeDiff / 60;

  if (timeDiff < 60) {
    if (timeDiff < 1) return "Just received.";

    return `${Math.floor(timeDiff)} minutes ago.`;
  }

  if (timeDiff > 60) {
    // Convert time difference from minutes to hours
    timeDiff = timeDiff / 60;

    if (timeDiff === 24) return "1 day ago.";

    if (timeDiff < 24) return `${Math.floor(timeDiff)} hours ago.`;

    if (timeDiff > 24) {
      // Convert time difference from hours to days
      timeDiff = timeDiff / 24;

      return `${Math.floor(timeDiff)} days ago.`;
    }
  }

  if (timeDiff === 60) return "1 hour ago.";
};
