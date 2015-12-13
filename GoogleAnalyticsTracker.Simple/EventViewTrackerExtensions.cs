using System.Threading.Tasks;

using GoogleAnalyticsTracker.Core;
using GoogleAnalyticsTracker.Core.TrackerParameters;

namespace GoogleAnalyticsTracker.Simple
{
    public static class PageViewTrackerExtensions
    {
        public static async Task<TrackingResult> TrackEventViewAsync(this SimpleTracker tracker, string category, string action, string label, long value = 1)
        {
            var eventTrackingParameters = new EventTracking
            {
                Category = category,
                Action = action,
                Label = label,
                Value = value,
                CacheBuster = tracker.AnalyticsSession.GenerateCacheBuster()
            };

            return await tracker.TrackAsync(eventTrackingParameters);
        }
    }
}
