import { Sidebar, Topbar } from "@/components/Chrome";

export default function AppLayout({ children }: { children: React.ReactNode }) {
  return (
    <div className="shell">
      <Sidebar />
      <div style={{ flex: 1, display: "flex", flexDirection: "column" }}>
        <Topbar />
        <main className="app-main">{children}</main>
      </div>
    </div>
  );
}
