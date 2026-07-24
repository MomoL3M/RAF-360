import { useState } from "react";
import { Sidebar, Topbar } from "./components/Chrome.jsx";
import Onboarding from "./screens/Onboarding.jsx";
import Dashboard from "./screens/Dashboard.jsx";
import Actions from "./screens/Actions.jsx";
import Calendar from "./screens/Calendar.jsx";
import Treasury from "./screens/Treasury.jsx";
import Documents from "./screens/Documents.jsx";
import Factures from "./screens/Factures.jsx";
import DataRoom from "./screens/DataRoom.jsx";
import Assistant from "./screens/Assistant.jsx";

const SCREENS = {
  dash: Dashboard,
  actions: Actions,
  calendar: Calendar,
  cash: Treasury,
  docs: Documents,
  factures: Factures,
  dataroom: DataRoom,
  assistant: Assistant,
};

export default function App() {
  const [onboarded, setOnboarded] = useState(false);
  const [page, setPage] = useState("dash");

  if (!onboarded) return <Onboarding onFinish={() => setOnboarded(true)} />;

  const Screen = SCREENS[page] || Dashboard;
  return (
    <div className="shell">
      <Sidebar page={page} onNav={setPage} />
      <div style={{ flex: 1, display: "flex", flexDirection: "column" }}>
        <Topbar />
        <main><Screen onNav={setPage} /></main>
      </div>
    </div>
  );
}
